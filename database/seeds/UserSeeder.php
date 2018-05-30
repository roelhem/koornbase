<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = factory(\App\User::class)->create(['name' => 'admin', 'email' => 'admin@roelweb.com']);
        $admin->assignRole('admin');
    }
}
