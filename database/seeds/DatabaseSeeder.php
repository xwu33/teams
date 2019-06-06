<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Eloquent::unguard();
        $this->call(UsersTableSeeder::class);
        $this->call(ModelRoleTableSeeder::class);
        $this->call(ExamsTableSeeder::class);
        $this->call(SignupsTableSeeder::class);
        //$this->call(SettingsTableSeeder::class);
        $this->command->info("The Farmer has Planted the Seeds");
    }
}
