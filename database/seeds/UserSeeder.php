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
        $admin = factory(\App\User::class)->create(['name' => 'admin', 'email' => 'admin@koornbase.test']);

        if($admin instanceof \App\User) {
            $admin->assignNode('Admin');
        }

        $admin = factory(\App\User::class)->create(['name' => 'super', 'email' => 'super@koornbase.test']);

        if($admin instanceof \App\User) {
            $admin->assignNode('Super');
        }



    }
}
