<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePermissionTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableNames = config('permission.table_names');
        $foreignKeys = config('permission.foreign_keys');

        Schema::create($tableNames['permissions'], function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
        });

        //Insert permissions
        DB::table('permissions')->insert(array(
            array(
              'id'=>1,
              'name'=>'User Self',
              'guard_name'=>'web',
              'created_at'=>date("Y-m-d H:i:s") ,
              'updated_at'=>date("Y-m-d H:i:s") ),
            array(
              'id'=>2,
              'name'=>'User All',
              'guard_name'=>'web',
              'created_at'=>date("Y-m-d H:i:s") ,
              'updated_at'=>date("Y-m-d H:i:s") ),
            array(
              'id'=>3,
              'name'=>'Exam Self',
              'guard_name'=>'web',
              'created_at'=>date("Y-m-d H:i:s") ,
              'updated_at'=>date("Y-m-d H:i:s") ),
            array(
              'id'=>4,
              'name'=>'Exam All',
              'guard_name'=>'web',
              'created_at'=>date("Y-m-d H:i:s") ,
              'updated_at'=>date("Y-m-d H:i:s") ),
            array(
              'id'=>5,
              'name'=>'Signup Self',
              'guard_name'=>'web',
              'created_at'=>date("Y-m-d H:i:s") ,
              'updated_at'=>date("Y-m-d H:i:s") ),
            array(
              'id'=>6,
              'name'=>'Signup All',
              'guard_name'=>'web',
              'created_at'=>date("Y-m-d H:i:s") ,
              'updated_at'=>date("Y-m-d H:i:s") ),
            array(
              'id'=>7,
              'name'=>'Administer roles & permissions',
              'guard_name'=>'web',
              'created_at'=>date("Y-m-d H:i:s") ,
              'updated_at'=>date("Y-m-d H:i:s") ),
        ));

        Schema::create($tableNames['roles'], function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
        });

        DB::table('roles')->insert(array(
            array(
              'id'=>1,
              'name'=>'Admin',
              'guard_name'=>'web',
              'created_at'=>date("Y-m-d H:i:s") ,
              'updated_at'=>date("Y-m-d H:i:s") ),
            array(
              'id'=>2,
              'name'=>'Faculty',
              'guard_name'=>'web',
              'created_at'=>date("Y-m-d H:i:s") ,
              'updated_at'=>date("Y-m-d H:i:s") ),
            array(
              'id'=>3,
              'name'=>'Student',
              'guard_name'=>'web',
              'created_at'=>date("Y-m-d H:i:s") ,
              'updated_at'=>date("Y-m-d H:i:s") ),
        ));

        Schema::create($tableNames['model_has_permissions'], function (Blueprint $table) use ($tableNames, $foreignKeys) {
            $table->integer('permission_id')->unsigned();
            $table->morphs('model');

            $table->foreign('permission_id')
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

            $table->primary(['permission_id', 'model_id', 'model_type']);
        });

        Schema::create($tableNames['model_has_roles'], function (Blueprint $table) use ($tableNames, $foreignKeys) {
            $table->integer('role_id')->unsigned();
            $table->morphs('model');

            $table->foreign('role_id')
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade');

            $table->primary(['role_id', 'model_id', 'model_type']);
        });

        Schema::create($tableNames['role_has_permissions'], function (Blueprint $table) use ($tableNames) {
            $table->integer('permission_id')->unsigned();
            $table->integer('role_id')->unsigned();

            $table->foreign('permission_id')
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

            $table->foreign('role_id')
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade');

            $table->primary(['permission_id', 'role_id']);
        });

        DB::table('role_has_permissions')->insert(array(
            array(
              'permission_id'=>1,
              'role_id' => 1
            ),
            array(
              'permission_id'=>2,
              'role_id' => 1
            ),
            array(
              'permission_id'=>3,
              'role_id' => 1
            ),
            array(
              'permission_id'=>4,
              'role_id' => 1
            ),
            array(
              'permission_id'=>5,
              'role_id' => 1
            ),
            array(
              'permission_id'=>6,
              'role_id' => 1
            ),
            array(
              'permission_id'=>1,
              'role_id' => 2
            ),
            array(
              'permission_id'=>3,
              'role_id' => 2
            ),
            array(
              'permission_id'=>5,
              'role_id' => 2
            ),
            array(
              'permission_id'=>6,
              'role_id' => 2
            ),
            array(
              'permission_id'=>1,
              'role_id' => 3
            ),
            array(
              'permission_id'=>5,
              'role_id' => 3
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
        $tableNames = config('permission.table_names');

        Schema::drop($tableNames['role_has_permissions']);
        Schema::drop($tableNames['model_has_roles']);
        Schema::drop($tableNames['model_has_permissions']);
        Schema::drop($tableNames['roles']);
        Schema::drop($tableNames['permissions']);
    }
}
