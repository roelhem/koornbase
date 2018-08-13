<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws
     */
    public function run()
    {
        $admin = factory(\App\User::class)->create(['name' => 'admin', 'email' => 'admin@roelweb.com']);

        if($admin instanceof \App\User) {
            $admin->assignNode('Admin');
        }

    }
}
