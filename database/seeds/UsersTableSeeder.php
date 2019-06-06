<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('users')->insert(array(
          array(
          'username'=> 'nickfury',
          'password' => bcrypt('IamIronman!'),
          'email' => 'nickfury@etech.as.ua.edu',
          'prefix'=>'Mr.',
          'first_name' => 'Nick',
          'middle_initial'=>'J',
          'last_name' => 'Fury',
          'suffix'=>'',
          'phone_number'=>'5555555555',
          'active'=>1,
          'verified' => 1,
          'is_cas'=>0,
          'created_at'=>date("Y-m-d H:i:s") ,
          'updated_at'=>date("Y-m-d H:i:s") ),
          array(
          'username'=> 'tonystark',
          'password' => bcrypt('IamIronman!'),
          'email' => 'tonystark@etech.as.ua.edu',
          'prefix'=>'Mr.',
          'first_name' => 'Tony',
          'middle_initial'=>'E',
          'last_name' => 'Stark',
          'suffix'=>'',
          'phone_number'=>'5555555555',
          'active'=>1,
          'verified' => 1,
          'is_cas'=>0,
          'created_at'=>date("Y-m-d H:i:s") ,
          'updated_at'=>date("Y-m-d H:i:s") ),
          array(
          'username'=> 'steverogers',
          'password' => bcrypt('IamIronman!'),
          'email' => 'steverogers@etech.as.ua.edu',
          'prefix'=>'Mr.',
          'first_name' => 'Steve',
          'middle_initial'=>'',
          'last_name' => 'Rogers',
          'suffix'=>'',
          'phone_number'=>'5555555555',
          'active'=>1,
          'verified' => 1,
          'is_cas'=>0,
          'created_at'=>date("Y-m-d H:i:s") ,
          'updated_at'=>date("Y-m-d H:i:s") ),
          array(
          'username'=> 'peterparker',
          'password' => bcrypt('IamIronman!'),
          'email' => 'peterparker@etech.as.ua.edu',
          'prefix'=>'Mr.',
          'first_name' => 'Peter',
          'middle_initial'=>'B',
          'last_name' => 'Parker',
          'suffix'=>'',
          'phone_number'=>'5555555555',
          'active'=>1,
          'verified' => 1,
          'is_cas'=>0,
          'created_at'=>date("Y-m-d H:i:s") ,
          'updated_at'=>date("Y-m-d H:i:s") )
      ));
    }
}
