<?php
namespace App\Helpers;

class Apidocs {
    public static function data() {
        $data = [];
        $data[] = [
            'title' => 'Example api',
            'method' => 'any',
            'endpoint' => str_replace( 'me', '', route( 'api.profile.me' ) ),
            'description' => 'Example api description',
        ];

        $data[] = self::profile();
        $data[] = self::task();

        return json_decode( json_encode( $data ), false );
    }

    public static function profile() {
        return [
            'title' => 'View profile',
            'method' => 'GET',
            'endpoint' => route( 'api.profile.me' ),
            'description' => __( 'locale.description.profile_api', ['brandname' => config( 'app.name' )] ),
        ];
    }
    public static function task() {
        return [
            'title' => 'View Task',
            'method' => 'GET',
            'endpoint' => route( 'api.task.index' ),
            'description' => 'Show all task',
        ];
    }
}