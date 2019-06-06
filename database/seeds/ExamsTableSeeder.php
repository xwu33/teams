<?php

use Illuminate\Database\Seeder;

class ExamsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

      $currentYear = date("Y");
      $currentMonth = date("m");
      $currentSchoolYear = $currentYear;
      if($currentMonth < 8) {
        $currentSchoolYear--;
      }
      DB::table('exams')->insert(array(
          array(
            'id' => 1,
            'owner_id' => 2,
            'course_name' => 'STARK 101',
            'location' => 'Avengers Tower 302',
            'max_proctors' => 5,
            'max_students' => 100,
            'date' => date("Y-m-d",strtotime('tomorrow')),
            'start_time' => '10:00',
            'end_time' => '10:50',
            'school_year' => $currentSchoolYear,
            'locked' => 0,
            'restricted' => 0,
            'created_at'=>date("Y-m-d H:i:s") ,
            'updated_at'=>date("Y-m-d H:i:s")
          ),
          array(
            'id' => 2,
            'owner_id' => 2,
            'course_name' => 'STARK 201',
            'location' => 'Avengers Tower Training Center',
            'max_proctors' => 1,
            'max_students' => 10,
            'date' => date("Y-m-d",strtotime('+3 day')),
            'start_time' => '12:00',
            'end_time' => '14:30',
            'school_year' => $currentSchoolYear,
            'locked' => 0,
            'restricted' => 0,
            'created_at'=>date("Y-m-d H:i:s") ,
            'updated_at'=>date("Y-m-d H:i:s")
          ),
          array(
            'id' => 3,
            'owner_id' => 2,
            'course_name' => 'STARK 201',
            'location' => 'Avengers Tower Training Center',
            'max_proctors' => 1,
            'max_students' => 10,
            'date' => date("Y-m-d",strtotime('-1 year')),
            'start_time' => '12:00',
            'end_time' => '14:30',
            'school_year' => $currentSchoolYear-1,
            'locked' => 0,
            'restricted' => 0,
            'created_at'=>date("Y-m-d H:i:s") ,
            'updated_at'=>date("Y-m-d H:i:s")
          ),
          array(
            'id' => 4,
            'owner_id' => 2,
            'course_name' => 'STARK 201',
            'location' => 'Avengers Tower Training Center',
            'max_proctors' => 1,
            'max_students' => 10,
            'date' => date("Y-m-d",strtotime('+7 day')),
            'start_time' => '12:00',
            'end_time' => '14:30',
            'school_year' => $currentSchoolYear,
            'locked' => 0,
            'restricted' => 0,
            'created_at'=>date("Y-m-d H:i:s") ,
            'updated_at'=>date("Y-m-d H:i:s")
          ),
          array(
            'id' => 5,
            'owner_id' => 2,
            'course_name' => 'STARK 201',
            'location' => 'Avengers Tower Training Center',
            'max_proctors' => 1,
            'max_students' => 10,
            'date' => date("Y-m-d",strtotime('+7 day')),
            'start_time' => '12:00',
            'end_time' => '14:30',
            'school_year' => $currentSchoolYear,
            'locked' => 0,
            'restricted' => 0,
            'created_at'=>date("Y-m-d H:i:s") ,
            'updated_at'=>date("Y-m-d H:i:s")
          ),
          array(
            'id' => 6,
            'owner_id' => 2,
            'course_name' => 'STARK 201',
            'location' => 'Avengers Tower Training Center',
            'max_proctors' => 1,
            'max_students' => 10,
            'date' => date("Y-m-d",strtotime('+1 day')),
            'start_time' => '12:00',
            'end_time' => '14:30',
            'school_year' => $currentSchoolYear,
            'locked' => 0,
            'restricted' => 0,
            'created_at'=>date("Y-m-d H:i:s") ,
            'updated_at'=>date("Y-m-d H:i:s")
          )

      ));
    }
}
