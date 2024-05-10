<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\Contracts\SettingsRepository;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SystemController extends AdminBaseController {
    protected $settings;

    /**
     * SettingsController constructor.
     *
     * @param  SettingsRepository  $settings
     */
    public function __construct( SettingsRepository $settings ) {
        $this->settings = $settings;
    }

    /**
     * Update all system settings.
     *
     * @return Application|Factory|View
     * @throws AuthorizationException
     */
    public function environments() {

        if ( $this->checks() ) {
            return redirect()->back()->with( [
                'status' => 'error',
                'message' => 'Sorry! This option is not available in demo mode',
            ] );
        }

        $this->authorize( 'view_env' );

        $breadcrumbs = [
            ['link' => url( config( 'app.admin_path' ) . "/dashboard" ), 'name' => __( 'locale.menu.Dashboard' )],
            ['link' => url( config( 'app.admin_path' ) . "/dashboard" ), 'name' => __( 'locale.menu.Settings' )],
            ['name' => __( 'locale.menu.All Settings' )],
        ];

        $environments = $_ENV;

        return view( 'admin.systems.environments', compact( 'environments' ) );
    }
    /**
     * Update all system settings.
     *
     */
    public function config() {

        if ( $this->checks() ) {
            return redirect()->back()->with( [
                'status' => 'error',
                'message' => 'Sorry! This option is not available in demo mode',
            ] );
        }

        $this->authorize( 'view_config' );

        $config = config()->all();

        // return $config;

        return view( 'admin.systems.config', compact( 'config' ) );
    }

    /**
     * Scan filesystem
     */
    public function scanDir() {

        $this->authorize( 'view_filemanager' );

        if ( $this->checks() ) {
            return redirect()->back()->with( [
                'status' => 'error',
                'message' => 'Sorry! This option is not available in demo mode',
            ] );
        }

        return view( 'admin.systems.scan' );
    }

}
