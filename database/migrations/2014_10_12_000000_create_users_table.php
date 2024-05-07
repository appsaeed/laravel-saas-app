<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CreateUsersTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create( 'users', function ( Blueprint $table ) {
            $table->id();
            $table->text( 'uid' )->default( Str::uuid() );
            $table->string( 'api_token' )->nullable();
            $table->string( 'first_name' );
            $table->string( 'last_name' )->nullable();
            $table->string( 'email' )->unique();
            $table->timestamp( 'email_verified_at' )->nullable();
            $table->string( 'password' )->nullable();
            $table->boolean( 'status' )->default( true );
            $table->text( 'image' )->nullable();
            $table->boolean( 'is_admin' )->default( false );
            $table->boolean( 'is_customer' )->default( true );
            $table->string( 'active_portal' )->default( 'customer' );
            $table->boolean( 'two_factor' )->default( false );
            $table->integer( 'two_factor_code' )->nullable();
            $table->dateTime( 'two_factor_expires_at' )->nullable();
            $table->string( 'two_factor_backup_code' )->nullable();
            $table->string( 'locale' )->default( '' );
            $table->string( 'timezone' )->default( '' );
            $table->timestamp( 'last_access_at' )->nullable();
            $table->rememberToken();
            $table->timestamps();
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists( 'users' );
    }
}
