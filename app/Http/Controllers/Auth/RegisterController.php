<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Extensions;
use App\Models\Language;
use App\Models\PaymentMethods;
use App\Repositories\Contracts\AccountRepository;
use App\Rules\Phone;
use Arcanedev\NoCaptcha\Rules\CaptchaRule;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Stripe\Exception\ApiErrorException;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default, this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
     */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/login';

    /**
     * @var AccountRepository
     */
    protected $account;

    protected $subscriptions;

    /**
     * @param __construct
     */
    protected $twilio;

    /**
     * RegisterController constructor.
     *
     * @param  AccountRepository  $account
     */
    public function __construct(AccountRepository $account)
    {
        $this->middleware('guest');
        $this->account = $account;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data): \Illuminate\Contracts\Validation\Validator
    {
        $rules = [
            'first_name' => ['required', 'string', 'max:255'],
            'email'      => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'   => ['required', 'string', 'min:8', 'confirmed'],
            'phone'      => ['nullable', new Phone($data['phone'])],
            'country'    => ['required', 'string'],
            'locale'     => ['required', 'string', 'min:2', 'max:2'],
        ];

        if (config('no-captcha.registration')) {
            $rules['g-recaptcha-response'] = ['required', new CaptchaRule()];
        }

        return Validator::make($data, $rules);
    }

    /**
     * @throws ApiErrorException
     */
    public function register(Request $request)
    {

        $data = $request->except('_token');

        $rules = [
            'first_name' => ['required', 'string', 'max:255'],
            'email'      => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'   => ['required', 'string', 'min:8', 'confirmed'],
            'phone'      => ['nullable', new Phone($request->phone)],
            'country'    => ['required', 'string'],
            'locale'     => ['required', 'string', 'min:2', 'max:2'],
        ];

        if (config('no-captcha.registration')) {
            $rules['g-recaptcha-response'] = ['required', new CaptchaRule()];
        }

        $v = Validator::make($data, $rules);

        if ($v->fails()) {
            return redirect()->route('register')->withInput()->withErrors($v->errors());
        }

        $user = $this->account->register($data);


        $user->email_verified_at = Carbon::now();
        $user->save();


        return redirect()->route('register')->with([
            'status'  => 'success',
            'message' => __('locale.auth.registration_successfully_done'),
        ]);
    }

    // Register
    public function showRegistrationForm()
    {
        $pageConfigs = [
            'blankPage' => true,
        ];

        $languages = Language::where('status', 1)->get();

        return view('/auth/register', [
            'pageConfigs'     => $pageConfigs,
            'languages'       => $languages
        ]);
    }
}
