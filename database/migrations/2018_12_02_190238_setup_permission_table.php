<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SetupPermissionTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     * @throws Throwable
     */
    public function up() {

        // Create table for storing roles
        Schema::create( 'roles', function ( Blueprint $table ) {
            $table->id();
            $table->text( 'uid' );
            $table->string( 'name' )->unique();
            $table->boolean( 'status' )->default( true );
            $table->timestamps();
        } );

        // Create table for associating roles to users (Many-to-Many)
        Schema::create( 'role_user', function ( Blueprint $table ) {
            $table->unsignedBigInteger( 'user_id' );
            $table->unsignedBigInteger( 'role_id' );

            $table->foreign( 'user_id' )->references( 'id' )->on( 'users' )->onDelete( 'cascade' );
            $table->foreign( 'role_id' )->references( 'id' )->on( 'roles' )->onDelete( 'cascade' );

            $table->primary( ['user_id', 'role_id'] );
        } );

        // Create table for storing permissions
        Schema::create( 'permissions', function ( Blueprint $table ) {
            $table->id();
            $table->text( 'uid' );
            $table->unsignedBigInteger( 'role_id' );
            $table->string( 'name' );
            $table->timestamps();

            $table->foreign( 'role_id' )->references( 'id' )->on( 'roles' )->onDelete( 'cascade' );
        } );

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop( 'permissions' );
        Schema::drop( 'role_user' );
        Schema::drop( 'roles' );
    }
}
