<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrenciesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create( 'currencies', function ( Blueprint $table ) {
            $table->id();
            $table->text( 'uid' );
            $table->unsignedBigInteger( 'user_id' )->nullable();
            $table->string( 'name' );
            $table->string( 'code' );
            $table->string( 'format' );
            $table->boolean( 'status' )->default( true );

            $table->timestamps();

            // foreign
            $table->foreign( 'user_id' )->references( 'id' )->on( 'users' )->onDelete( 'cascade' );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists( 'currencies' );
    }
}
