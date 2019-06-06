<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('exams', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('owner_id');
            $table->string('course_name',100)->index();
            $table->string('location',100)->index();
            $table->integer('max_proctors')->index()->default(0);
            $table->integer('max_students')->index()->default(0);
            $table->timestamp('date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('school_year')->index();
            $table->boolean('locked')->default(0)->index();
            $table->boolean('restricted')->default(0)->index();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('owner_id')->references('id')->on('users');

      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exams');
    }
}
