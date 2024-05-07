<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create( 'chat_boxes', function ( Blueprint $table ) {

            $table->id();
            $table->text( 'uid' );
            $table->unsignedBigInteger( 'user_id' );
            $table->unsignedBigInteger( 'todo_id' );
            $table->string( 'from' )->nullable();
            $table->string( 'to', 20 );
            $table->integer( 'notification' )->nullable();

            $table->timestamps();

            // foreign
            $table->foreign( 'user_id' )->references( 'id' )->on( 'users' )->onDelete( 'cascade' );
            $table->foreign( 'todo_id' )->references( 'id' )->on( 'todos' )->onDelete( 'cascade' );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists( 'chat_boxes' );
    }
};
