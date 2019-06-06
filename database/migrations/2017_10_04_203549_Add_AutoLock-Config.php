<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAutoLockConfig extends Migration
{
    /**
     * stub migration from abandoned idea
     *
     * @return void
     */
     public function up()
     {

       //add locked attribute to table

       //add autolock value
       /*DB::table('settings')->insert(array(
         array(
           'option'=>'Auto Lock',
           'value' => '5'
         )
       ));*/
     }

     /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       DB::table('settings')->where('option','Auto Lock')->delete();
     }
   }
