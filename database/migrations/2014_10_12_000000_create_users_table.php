<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username',30)->unique();
            $table->string('password');
            $table->rememberToken();
            $table->string('email',50)->index();
            $table->string('prefix',5)->nullable();
            $table->string('first_name',30)->index();
            $table->string('middle_initial',1)->nullable();
            $table->string('last_name',30)->index();
            $table->string('suffix', 5)->nullable();
            $table->string('phone_number');
            $table->boolean('active')->default(1);
            $table->boolean('verified')->default(0);
            $table->boolean('is_cas')->default(0);
            $table->timestamp('last_logged_in');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
