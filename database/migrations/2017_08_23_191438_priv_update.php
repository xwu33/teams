<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PrivUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        app()['cache']->forget('spatie.permission.cache');
        $role = Role::findByName('Admin');
        $role->revokePermissionTo('Signup Self');

        $role = Role::findByName('Faculty');
        $role->revokePermissionTo('Signup Self');
        $role->revokePermissionTo('Signup All');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      app()['cache']->forget('spatie.permission.cache');
      $role = Role::findByName('Admin');
      $role->givePermissionTo('Signup Self');

      $role = Role::findByName('Faculty');
      $role->givePermissionTo('Signup Self');
      $role->givePermissionTo('Signup All');
    }
  }
