<?php

use Illuminate\Database\Seeder;

class SignupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('signups')->insert(array(
          array(
            'id' => 1,
            'user_id' => 4,
            'exam_id' => 2,
            'created_at'=>date("Y-m-d H:i:s") ,
            'updated_at'=>date("Y-m-d H:i:s")
          ),
          array(
            'id' => 2,
            'user_id' => 4,
            'exam_id' => 1,
            'created_at'=>date("Y-m-d H:i:s") ,
            'updated_at'=>date("Y-m-d H:i:s")
          ),
          array(
            'id' => 3,
            'user_id' => 4,
            'exam_id' => 3,
            'created_at'=>date("Y-m-d H:i:s") ,
            'updated_at'=>date("Y-m-d H:i:s")
          ),
          array(
            'id' => 4,
            'user_id' => 4,
            'exam_id' => 4,
            'created_at'=>date("Y-m-d H:i:s") ,
            'updated_at'=>date("Y-m-d H:i:s")
          )
      ));
      //log the signup in the exam row
      DB::table('exams')->where("id",2)->update(['current_signups' => 1]);
    }
}
