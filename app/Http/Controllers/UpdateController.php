<?php

namespace App\Http\Controllers;

use App\Helpers\DatabaseManager;
use App\Helpers\EnvironmentManager;
use App\Helpers\MigrationsHelper;
use App\Helpers\PermissionsChecker;
use App\Helpers\RequirementsChecker;
use App\Library\Tool;
use App\Models\AppConfig;
use Database\Seeders\Countries;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Output\BufferedOutput;


class UpdateController extends Controller
{
        use MigrationsHelper;

        /**
         * @var RequirementsChecker
         */
        protected $requirements;
        protected $EnvironmentManager;


        /**
         * @param  RequirementsChecker  $checker
         * @param  EnvironmentManager  $environmentManager
         * @param  DatabaseManager  $databaseManager
         */
        public function __construct(RequirementsChecker $checker, EnvironmentManager $environmentManager, DatabaseManager $databaseManager)
        {
                $this->requirements       = $checker;
                $this->EnvironmentManager = $environmentManager;
                $this->databaseManager    = $databaseManager;
        }

        public function welcome()
        {
                if (config('app.stage') == 'demo') {
                        return redirect()->back()->with([
                                'status'  => 'error',
                                'message' => 'Sorry!! This feature is not available in demo mode',
                        ]);
                }

                if (config('app.version') == '1.1.0') {
                        return redirect()->back()->with([
                                'status'  => 'success',
                                'message' => 'You are already in latest version',
                        ]);
                }

                $phpSupportInfo = $this->requirements->checkPHPversion(
                        config('installer.core.minPhpVersion')
                );
                $requirements   = $this->requirements->check(
                        config('installer.requirements')
                );

                $pageConfigs = [
                        'bodyClass' => "bg-full-screen-image",
                        'blankPage' => true,
                ];

                $getPermissions = new PermissionsChecker();

                $permissions = $getPermissions->check(
                        config('installer.permissions')
                );

                return view('Installer.update.welcome', compact('requirements', 'phpSupportInfo', 'pageConfigs', 'permissions'));
        }


        /**
         * @param  Request  $request
         * @param  BufferedOutput  $outputLog
         *
         * @return RedirectResponse
         */
        public function verifyProduct(Request $request, BufferedOutput $outputLog): RedirectResponse
        {




                $purchase_code = rand(10, 9999);
                $domain_name   = config('app.url');
                $input         = trim($domain_name, '/');
                $urlParts      = parse_url($input);
                $domain_name   = preg_replace('/^www\./', '', $urlParts['host']);

                $post_data = [
                        'purchase_code' => $purchase_code,
                        'domain'        => $domain_name,
                ];

                $data = json_encode(['status' => 'success', 'data' => $post_data]);

                $get_data = json_decode($data, true);

                if (is_array($get_data) && array_key_exists('status', $get_data)) {
                        if ($get_data['status'] == 'success') {

                                try {

                                        Artisan::call('optimize:clear');
                                        Artisan::call('migrate', ['--force' => true], $outputLog);
                                        Tool::versionSeeder(config('app.version'));

                                        AppConfig::setEnv('APP_VERSION', '1.1.0');

                                        return redirect()->route('login')->with([
                                                'status'  => 'success',
                                                'message' => 'You have successfully updated your application.',
                                        ]);
                                } catch (Exception $e) {

                                        return redirect()->route('Updater::welcome')->with([
                                                'status'  => 'error',
                                                'message' => $e->getMessage(),
                                        ]);
                                }
                        }

                        return redirect()->route('Updater::welcome')->with([
                                'message' => $get_data['msg'],
                                'status'  => 'error',
                        ]);
                }

                return redirect()->route('Updater::welcome')->with([
                        'message' => 'Invalid request',
                        'status'  => 'error',
                ]);
        }
}
