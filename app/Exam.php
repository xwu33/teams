<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Setting;


class Exam extends Model
{
    use SoftDeletes;

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'owner_id', 'course_name', 'location', 'date', 'start_time',
        'end_time', 'max_proctors', 'max_students', 'school_year', 'locked'
    ];

    public static function getSemester($today = false) {

      $fallMonth = date("m",strtotime(Setting::byName('Fall Start Month')));
      $springMonth = date("m",strtotime(Setting::byName('Spring Start Month')));
      $summerMonth = date("m",strtotime(Setting::byName('Summer Start Month')));


      if($today) {
        $date = time();
      }
      else {
        $date = strtotime($this->date);
      }

      $app = app();

      $semester = $app->make('stdClass');
      $semester->year = date("Y",$date);
      $month = date("m",$date);

      if($month >= $springMonth) {
        $semester->semester = "Spring";
        $semester->startMonth = $springMonth;
        $semester->endMonth = $summerMonth-1;
      }
      if($month >= $summerMonth) {
        $semester->semester = "Summer";
        $semester->startMonth = $summerMonth;
        $semester->endMonth = $fallMonth-1;
      }
      if($month >= $fallMonth) {
        $semester->semester = "Fall";
        $semester->startMonth = $fallMonth;
        $semester->endMonth = $springMonth-1;
      }

      if($semester->endMonth <= 0) {
        $semester->endMonth += 12;
      }

      return $semester;

    }

  }
