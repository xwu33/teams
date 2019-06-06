<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSemesterBoundaryConfig extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      DB::table('settings')->insert(array(
        array(
          'option'=>'Fall Start Month',
          'value' => 'August'
        ),
        array(
          'option'=>'Spring Start Month',
          'value' => 'January'
        ),
        array(
          'option'=>'Summer Start Month',
          'value' => 'June'
        )
      ));
    }

    /**
    * Reverse the migrations.
    *
    * @return void
    */
    public function down()
    {
       DB::table('settings')->where('option','LIKE','%Start Month')->delete();
    }
  }
