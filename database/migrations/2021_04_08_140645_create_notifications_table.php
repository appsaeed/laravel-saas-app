<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {

            $table->id();
            $table->uuid('uid')->default(DB::raw('(UUID())'));
            $table->unsignedBigInteger('user_id')->default(1);
            $table->unsignedBigInteger('created_by')->default(1);
            $table->string('for')->default('customer');
            $table->string('type')->default('task');
            $table->text('name')->nullable();
            $table->text('message')->nullable();
            $table->boolean('mark_read')->default(false);
            $table->boolean('mark_open')->default(false);

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));


            // foreign
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
