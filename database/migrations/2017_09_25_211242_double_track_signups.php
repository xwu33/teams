<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DoubleTrackSignups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exams',function(Blueprint $table) {
          $table->integer('current_signups')->default(0)->after('max_proctors');
        });

        $results = DB::table('exams')
        ->select(DB::raw("COUNT(signups.user_id) AS ExamSignups"),DB::raw('exams.id AS examID'))
        ->leftJoin('signups',function ($join) {
          $join->on('exams.id','=','signups.exam_id')
          ->whereNull('signups.deleted_at');
        })
        ->groupBy('exams.id')->get();
        foreach($results as $result) {
          DB::table('exams')->where('id',$result->examID)->update(['current_signups' => $result->ExamSignups]);
        }
      }

      /**
      * Reverse the migrations.
      *
      * @return void
      */
      public function down()
      {
        Schema::table('exams',function(Blueprint $table) {
          $table->dropColumn('current_signups');
        });
      }
    }
