<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\GeneralException;
use App\Http\Requests\Settings\StoreLanguageRequest;
use App\Http\Requests\Settings\UploadLanguageRequest;
use App\Models\Language;
use App\Models\User;
use App\Repositories\Contracts\LanguageRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class LanguageController extends AdminBaseController {
    protected LanguageRepository $languages;

    /**
     * CurrencyController constructor.
     *
     * @param  LanguageRepository  $languages
     */

    public function __construct( LanguageRepository $languages ) {
        $this->languages = $languages;
    }

    /**
     * view all active languages
     *
     * @return Application|Factory|View
     * @throws AuthorizationException
     */
    public function index(): Factory | View | Application {
        $this->authorize( 'view languages' );

        $breadcrumbs = [
            ['link' => url( config( 'app.admin_path' ) . "/dashboard" ), 'name' => __( 'locale.menu.Dashboard' )],
            ['link' => url( config( 'app.admin_path' ) . "/dashboard" ), 'name' => __( 'locale.menu.Settings' )],
            ['name' => __( 'locale.menu.Language' )],
        ];

        $languages = Language::cursor();

        return \view( 'admin.settings.Language.index', compact( 'languages', 'breadcrumbs' ) );
    }

    /**
     * add new language
     *
     * @return Application|Factory|View
     * @throws AuthorizationException
     */
    public function create(): Factory | View | Application {
        $this->authorize( 'new languages' );

        $breadcrumbs = [
            ['link' => url( config( 'app.admin_path' ) . "/dashboard" ), 'name' => __( 'locale.menu.Dashboard' )],
            ['link' => url( config( 'app.admin_path' ) . "/languages" ), 'name' => __( 'locale.menu.Language' )],
            ['name' => __( 'locale.settings.add_new' )],
        ];

        return \view( 'admin.settings.Language.new', compact( 'breadcrumbs' ) );
    }

    /**
     * store new language
     *
     * @param  StoreLanguageRequest  $request
     *
     * @return RedirectResponse
     */
    public function store( StoreLanguageRequest $request ): RedirectResponse {

        if ( $this->checks() ) {
            return redirect()->route( 'admin.languages.index' )->with( [
                'status' => 'error',
                'message' => 'Sorry! This option is not available in demo mode',
            ] );
        }

        $this->languages->store( $request->input() );

        return redirect()->route( 'admin.languages.index' )->with( [
            'status' => 'success',
            'message' => __( 'locale.settings.successfully_added' ),
        ] );

    }

    /**
     *
     * change status
     *
     * @param  Language  $language
     *
     * @return JsonResponse
     * @throws AuthorizationException
     * @throws GeneralException
     */
    public function activeToggle( Language $language ): JsonResponse {
        if ( $this->checks() ) {
            return response()->json( [
                'status' => 'error',
                'message' => 'Sorry! This option is not available in demo mode',
            ] );
        }

        try {

            $this->authorize( 'manage languages' );

            if ( $language->update( ['status' => !$language->status] ) ) {
                User::where( 'locale', $language->code )->update( [
                    'locale' => 'en',
                ] );

                return response()->json( [
                    'status' => 'success',
                    'message' => __( 'locale.settings.status_successfully_change' ),
                ] );
            }

            throw new GeneralException( __( 'locale.exceptions.something_went_wrong' ) );

        } catch ( ModelNotFoundException $exception ) {
            return response()->json( [
                'status' => 'error',
                'message' => $exception->getMessage(),
            ] );
        }
    }

    /**
     * @param  Language  $language
     *
     * @return RedirectResponse|BinaryFileResponse
     * @throws AuthorizationException
     */
    public function download( Language $language ): BinaryFileResponse | RedirectResponse {
        if ( $this->checks() ) {
            return redirect()->route( 'admin.languages.index' )->with( [
                'status' => 'error',
                'message' => 'Sorry! This option is not available in demo mode',
            ] );
        }

        $this->authorize( 'manage languages' );

        $zip = $this->languages->download( $language );

        return response()->download( $zip )->deleteFileAfterSend();

    }

    /**
     * @param  Language  $language
     *
     * @return Application|Factory|View
     * @throws AuthorizationException
     */
    public function upload( Language $language ): Factory | View | Application {

        $this->authorize( 'manage languages' );

        $breadcrumbs = [
            ['link' => url( config( 'app.admin_path' ) . "/dashboard" ), 'name' => __( 'locale.menu.Dashboard' )],
            ['link' => url( config( 'app.admin_path' ) . "/languages" ), 'name' => __( 'locale.menu.Language' )],
            ['name' => __( 'locale.settings.upload_language' )],
        ];

        return \view( 'admin.settings.Language.upload', compact( 'breadcrumbs', 'language' ) );
    }

    /**
     * upload language files
     *
     * @param  UploadLanguageRequest  $request
     * @param  Language  $language
     *
     * @return RedirectResponse
     */
    public function uploadLanguage( UploadLanguageRequest $request, Language $language ): RedirectResponse {
        if ( $this->checks() ) {
            return redirect()->route( 'admin.languages.index' )->with( [
                'status' => 'error',
                'message' => 'Sorry! This option is not available in demo mode',
            ] );
        }

        $this->languages->upload( $request->all(), $language );

        return redirect()->route( 'admin.languages.index' )->with( [
            'status' => 'success',
            'message' => __( 'locale.settings.upload' ),
        ] );

    }

    /**
     * view language data
     *
     * @param  Language  $language
     *
     * @return Application|Factory|\Illuminate\Contracts\View\View
     * @throws AuthorizationException
     * @throws FileNotFoundException
     */

    public function show( Language $language ): \Illuminate\Contracts\View\View  | Factory | Application {
        $this->authorize( 'manage languages' );

        $breadcrumbs = [
            ['link' => url( config( 'app.admin_path' ) . "/dashboard" ), 'name' => __( 'locale.menu.Dashboard' )],
            ['link' => url( config( 'app.admin_path' ) . "/languages" ), 'name' => __( 'locale.menu.Language' )],
            ['name' => $language->name],
        ];

        $content = Yaml::dump( $language->getLocaleArrayFromFile() );

        return \view( 'admin.settings.Language.show', compact( 'breadcrumbs', 'language', 'content' ) );
    }

    /**
     * delete language
     *
     * @param  Language  $language
     *
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy( Language $language ): JsonResponse {
        if ( $this->checks() ) {
            return response()->json( [
                'status' => 'error',
                'message' => 'Sorry! This option is not available in demo mode',
            ] );
        }

        $this->authorize( 'delete languages' );

        $this->languages->destroy( $language );

        return response()->json( [
            'status' => 'success',
            'message' => __( 'locale.settings.successfully_deleted' ),
        ] );
    }

    /**
     * translate language file post
     *
     * @param  Language  $language
     * @param  Request  $request
     *
     * @return RedirectResponse
     */
    public function update( Language $language, Request $request ): RedirectResponse {

        if ( $this->checks() ) {
            return redirect()->route( 'admin.languages.index' )->with( [
                'status' => 'error',
                'message' => 'Sorry! This option is not available in demo mode',
            ] );
        }

        try {

            $callback = $language->updateFromYaml( $request->all()[$language->code] );
            if ( is_numeric( $callback ) ) {
                return redirect()->route( 'admin.languages.index' )->with( [
                    'status' => 'success',
                    'message' => 'Translate file was successfully updated',
                ] );
            }

            return redirect()->route( 'admin.languages.index' )->with( [
                'status' => 'error',
                'message' => __( 'locale.exceptions.something_went_wrong' ),
            ] );
        } catch ( ParseException $e ) {
            return redirect()->route( 'admin.languages.show', $language->uid )->with( [
                'status' => 'error',
                'message' => $e->getMessage(),
            ] );
        }
    }
}
