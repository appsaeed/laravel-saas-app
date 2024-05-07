<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CreateCountriesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create( 'countries', function ( Blueprint $table ) {
            $table->id();
            $table->text( 'uid' )->default( Str::uuid() );
            $table->string( 'name', 50 );
            $table->string( 'iso_code', 20 );
            $table->string( 'country_code', 5 );
            $table->boolean( 'status' )->default( true );

            $table->timestamps();
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists( 'countries' );
    }
}
