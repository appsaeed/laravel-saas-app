<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_box_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('box_id');
            $table->longText('message')->nullable();
            $table->longText('media_url')->nullable();
            $table->string('sms_type', 15)->default('sms');
            $table->enum('send_by', ['from', 'to'])->nullable();

            $table->timestamps();

            // foreign
            $table->foreign('box_id')->references('id')->on('chat_boxes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chat_box_messages');
    }
};
