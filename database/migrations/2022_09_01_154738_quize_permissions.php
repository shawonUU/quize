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
        Schema::create('quize_permissions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('quize_id')->unsigned();
            $table->foreign('quize_id')->references('id')->on('quizzes')->onDelete('cascade');
            $table->bigInteger('candidate_id')->unsigned();
            $table->foreign('candidate_id')->references('id')->on('candidates')->onDelete('cascade');
            $table->string('status')->default('assigned');
            $table->string('start_time')->nullable();
            $table->string('submit_time')->nullable();
            $table->string('result')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
