<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\GeneralException;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\LoginConfirmation;
use App\Repositories\Contracts\AccountRepository;
use Arcanedev\NoCaptcha\Rules\CaptchaRule;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Exception\NotReadableException;
use Intervention\Image\Facades\Image;
use Laravel\Socialite\Facades\Socialite;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use function in_array;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;


    protected array $supportedProviders = [
        'facebook',
        'google',
        'github',
        'twitter',
    ];

    /**
     * @var AccountRepository
     */
    protected AccountRepository $account;

    /**
     * Create a new controller instance.
     *
     * @param  AccountRepository  $account
     */
    public function __construct(AccountRepository $account)
    {
        $this->middleware('guest')->except('logout', 'avatar', 'downloadSampleFile');
        $this->account = $account;
    }

    // Login
    public function showLoginForm(): View|Factory|Redirector|Application|RedirectResponse
    {

        if (\auth()->check()) {
            return redirect(Helper::home_route());
        }

        $pageConfigs = [
            'bodyClass' => "bg-full-screen-image",
            'blankPage' => true,
        ];

        return view('/auth/login', [
            'pageConfigs' => $pageConfigs,
        ]);
    }


    /**
     * @param  Request  $request
     *
     * @return RedirectResponse
     */
    public function login(Request $request): RedirectResponse
    {
        $rules = [
            'email'       => 'required|string|email|min:3',
            'password'    => 'required|string|min:3|max:50',
            'remember_me' => 'boolean',
        ];

        if (config('no-captcha.login')) {
            $rules['g-recaptcha-response'] = ['required', new CaptchaRule()];
        }

        $messages = [
            'g-recaptcha-response.required' => __('locale.auth.recaptcha_required'),
            'g-recaptcha-response.captcha'  => __('locale.auth.recaptcha_required'),
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withInput($request->only('email'))->with([
                'status'  => 'warning',
                'message' => $validator->errors()->first(),
            ]);
        }

        try {

            $credentials = request(['email', 'password', 'status' => 1]);

            if (!Auth::attempt($credentials, $request->remember)) {
                return redirect()->back()->withInput($request->only('email'))->with([
                    'status'  => 'error',
                    'message' => __('locale.auth.failed'),
                ]);
            }

            if (!Auth::user()->status) {

                Auth::logout();

                return redirect()->back()->withInput($request->only('email'))->with([
                    'status'  => 'error',
                    'message' => __('locale.auth.disabled'),
                ]);
            }


            $user = Auth::user();

            $this->account->redirectAfterLogin($user);


            return redirect(Helper::home_route())->with([
                'status'  => 'success',
                'message' => __('locale.auth.welcome_come_back', ['name' => $user->displayName()]),
            ]);
        } catch (Exception $exception) {
            return redirect()->back()->with([
                'status'  => 'error',
                'message' => $exception->getMessage(),
            ]);
        }
    }


    /**
     * get customer avatar
     *
     * @param  User  $user
     *
     * @return mixed
     */
    public function avatar(User $user): mixed
    {

        if (!empty($user->imagePath())) {

            try {
                $image = Image::make($user->imagePath());
            } catch (NotReadableException) {
                $user->image = null;
                $user->save();

                $image = Image::make(public_path('images/profile/profile.jpg'));
            }
        } else {
            $image = Image::make(public_path('images/profile/profile.jpg'));
        }

        return $image->response();
    }

    /**
     * Log the user out of the application.
     *
     * @param  Request  $request
     *
     * @return Application|RedirectResponse|Response|Redirector
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function logout(Request $request): Response|Redirector|Application|RedirectResponse
    {

        if ($admin_id = Session::get('admin_user_id')) {
            // Impersonate mode, back to original User
            session()->forget('admin_user_id');
            session()->forget('admin_user_name');
            session()->forget('temp_user_id');
            session()->forget('permissions');

            auth()->loginUsingId((int) $admin_id);

            session(['permissions' => auth()->user()->getPermissions()]);

            return redirect()->route('admin.home');
        }

        $this->guard()->logout();

        $request->session()->invalidate();

        if ($this->loggedOut($request)) {
            return $this->loggedOut($request)->with([
                'status'  => 'success',
                'message' => 'Logout was successfully done',
            ]);
        } else {
            return redirect('/login');
        }
    }


    /**
     * Get the throttle key for the given request.
     *
     * @param  Request  $request
     *
     * @return string
     */
    protected function throttleKey(Request $request): string
    {
        return Str::lower($request->ip());
    }


    /**
     * redirect socialite
     *
     * @param $provider
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse]
     */

    public function redirectToProvider($provider): \Symfony\Component\HttpFoundation\RedirectResponse
    {

        if (config('app.stage') == 'demo') {
            return redirect()->route('login')->with([
                'status'  => 'error',
                'message' => 'Sorry! This option is not available in demo mode',
            ]);
        }

        if (!in_array($provider, $this->supportedProviders, true)) {
            return redirect()->route('user.home')->with([
                'status'  => 'error',
                'message' => __('locale.auth.socialite.unacceptable', ['provider' => $provider]),
            ]);
        }

        return Socialite::driver($provider)->redirect();
    }

    /**
     * handle socialite provider callback data
     *
     * @param $provider
     *
     * @return RedirectResponse
     */
    public function handleProviderCallback($provider): RedirectResponse
    {
        if (config('app.stage') == 'demo') {
            return redirect()->route('login')->with([
                'status'  => 'error',
                'message' => 'Sorry! This option is not available in demo mode',
            ]);
        }

        $data = Socialite::driver($provider)->user();

        try {
            $user = $this->account->findOrCreateSocial($provider, $data);

            if (!$user->status) {
                return redirect()->route('login')->with([
                    'status'  => 'error',
                    'message' => __('locale.auth.disabled'),
                ]);
            }

            $this->account->redirectAfterLogin($user);

            \auth()->login($user, true);

            if ($user->active_portal == 'customer') {
                return redirect()->route('user.home')->with([
                    'status'  => 'success',
                    'message' => __('locale.auth.welcome_come_back', ['name' => $user->displayName()]),
                ]);
            }

            return redirect()->route('admin.home')->with([
                'status'  => 'success',
                'message' => __('locale.auth.welcome_come_back', ['name' => $user->displayName()]),
            ]);
        } catch (GeneralException $e) {
            return redirect()->route('login')->with([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * download application all import sample file
     *
     * @return BinaryFileResponse
     */
    public function downloadSampleFile(): BinaryFileResponse
    {
        return \response()->download(storage_path('app/import_file_demo.csv'));
    }

    /*
     * test or debug or var_dump function
     */
    public function debug(): bool|string|RedirectResponse
    {
        if (config('app.stage') == 'demo') {
            return redirect()->route('login')->with([
                'status'  => 'error',
                'message' => 'Sorry! This option is not available in demo mode',
            ]);
        }

        $backUpCode = [];
        for ($i = 0; $i < 8; $i++) {
            $backUpCode[] = rand(100000, 999999);
        }

        return json_encode($backUpCode);
    }
}
