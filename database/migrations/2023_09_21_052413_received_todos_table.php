<?php

use App\Models\TodosReceived;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create( 'todos_received', function ( Blueprint $table ) {

            $table->id( 'id' );
            $table->text( 'uid' )->default( Str::uuid() );

            $table->unsignedBigInteger( 'user_id' );
            $table->unsignedBigInteger( 'todo_id' );
            $table->enum( 'status', TodosReceived::$status )->default( 'in_progress' );
            $table->boolean( 'accepted' )->default( false );
            $table->longText( 'options' )->nullable();
            $table->longText( 'data' )->nullable();

            $table->timestamp( 'created_at' )->default( DB::raw( 'CURRENT_TIMESTAMP' ) );
            $table->timestamp( 'updated_at' )->default( DB::raw( 'CURRENT_TIMESTAMP' ) );

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
        Schema::dropIfExists( 'todos_received' );
    }
};
