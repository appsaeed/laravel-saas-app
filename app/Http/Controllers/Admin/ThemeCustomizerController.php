<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Settings\ThemeCustomizerRequest;
use App\Models\AppConfig;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ThemeCustomizerController extends AdminBaseController {

    /**
     * @return Application|Factory|\Illuminate\Contracts\View\View|View
     * @throws AuthorizationException
     */
    public function index() {
        $this->authorize( 'general settings' );

        $breadcrumbs = [
            ['link' => url( config( 'app.admin_path' ) . "/dashboard" ), 'name' => __( 'locale.menu.Dashboard' )],
            ['link' => url( config( 'app.admin_path' ) . "/dashboard" ), 'name' => __( 'locale.menu.Theme Customizer' )],
            ['name' => __( 'locale.menu.Theme Customizer' )],
        ];

        return view( 'admin.ThemeCustomizer.index', compact( 'breadcrumbs' ) );
    }

    /**
     * @param  ThemeCustomizerRequest  $request
     *
     * @return RedirectResponse
     */
    public function postCustomizer( ThemeCustomizerRequest $request ): RedirectResponse {

        if ( $this->checks() ) {
            return redirect()->route( 'admin.theme.customizer' )->with( [
                'status' => 'error',
                'message' => 'Sorry! This option is not available in demo mode',
            ] );
        }

        $input = $request->all();

        if ( isset( $request->sidebarCollapsed ) ) {
            $sidebarCollapsed = "true";
        } else {
            $sidebarCollapsed = "false";
        }
        if ( isset( $request->pageHeader ) ) {
            $pageHeader = "true";
        } else {
            $pageHeader = "false";
        }
        if ( $request->navbarColor == 'custom' ) {
            $navbarColor = $request->navbarCustomColor;
        } else {
            $navbarColor = $request->navbarColor;
        }

        if ( $request->language_menu ) {
            AppConfig::setEnv( 'THEME_LANGUE_MENU', 'true' );
        } else {
            AppConfig::setEnv( 'THEME_LANGUE_MENU', 'false' );
        }

        if ( $request->theme_switch_menu ) {
            AppConfig::setEnv( 'THEME_SWITCH_MENU', 'true' );
        } else {
            AppConfig::setEnv( 'THEME_SWITCH_MENU', 'false' );
        }
        if ( $request->notifye_menu ) {
            AppConfig::setEnv( 'THEME_NOTIFYE_MENU', 'true' );
        } else {
            AppConfig::setEnv( 'THEME_NOTIFYE_MENU', 'false' );
        }
        if ( $request->profile_menu ) {
            AppConfig::setEnv( 'THEME_PROFILE_MENU', 'true' );
        } else {
            AppConfig::setEnv( 'THEME_PROFILE_MENU', 'false' );
        }

        AppConfig::setEnv( 'THEME_NAVBAR_COLOR', $navbarColor );
        AppConfig::setEnv( 'THEME_LAYOUT_TYPE', $input['mainLayoutType'] );
        AppConfig::setEnv( 'THEME_SKIN', $input['theme'] );
        AppConfig::setEnv( 'THEME_NAVBAR_TYPE', $input['navbarType'] );
        AppConfig::setEnv( 'THEME_FOOTER_TYPE', $input['footerType'] );
        AppConfig::setEnv( 'THEME_LAYOUT_WIDTH', $input['layoutWidth'] );
        AppConfig::setEnv( 'THEME_MENU_COLLAPSED', $sidebarCollapsed );
        AppConfig::setEnv( 'THEME_BREADCRUMBS', $pageHeader );

        return redirect()->route( 'admin.theme.customizer' )->with( [
            'status' => 'success',
            'message' => 'Theme customizer was successfully saved',
        ] );
    }
}
