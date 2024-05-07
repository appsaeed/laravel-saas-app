<?php

use App\Models\Todos;
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
        Schema::create( 'todos', function ( Blueprint $table ) {

            $table->id( 'id' );
            $table->text( 'uid' );
            $table->unsignedBigInteger( 'user_id' );
            $table->enum( 'status', Todos::$status_all );
            $table->string( 'name' )->nullable();
            $table->string( 'title' )->nullable();
            $table->longText( 'description' )->nullable();
            $table->longText( 'note' )->nullable();
            $table->dateTime( 'deadline' )->useCurrent();
            $table->dateTime( 'last_cron' )->useCurrent();
            $table->longText( 'assign_to' )->nullable();
            $table->longText( 'employees' )->nullable();
            $table->longText( 'reviewers' )->nullable();
            $table->longText( 'options' )->nullable();
            $table->string( 'update_message' )->nullable();

            $table->unsignedBigInteger( 'last_updated_by' )->nullable();
            $table->unsignedBigInteger( 'completed_by' )->nullable();

            $table->timestamps();

            // foreign
            $table->foreign( 'user_id' )->references( 'id' )->on( 'users' )->onDelete( 'cascade' );
            $table->foreign( 'completed_by' )->references( 'id' )->on( 'users' )->onDelete( 'cascade' );
            $table->foreign( 'last_updated_by' )->references( 'id' )->on( 'users' )->onDelete( 'cascade' );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists( 'todos' );
    }
};
