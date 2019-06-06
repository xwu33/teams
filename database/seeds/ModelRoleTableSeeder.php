<?php

use Illuminate\Database\Seeder;

class ModelRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('model_has_roles')->insert(array(
          array(
            'role_id'=>1,
            'model_id'=>1,
            'model_type'=>'bioproc\User'
          ),
          array(
            'role_id'=>2,
            'model_id'=>2,
            'model_type'=>'bioproc\User'
          ),
          array(
            'role_id'=>3,
            'model_id'=>3,
            'model_type'=>'bioproc\User'
          ),
          array(
            'role_id'=>3,
            'model_id'=>4,
            'model_type'=>'bioproc\User'
          )
      ));
    }
}
