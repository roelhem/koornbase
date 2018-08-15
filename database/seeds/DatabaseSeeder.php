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

        // First (system) account.
        $system = factory(\App\User::class)->create([
            'name' => 'system',
            'email' => 'system@koornbase.test'
        ]);

        $system->created_by = $system->id;

        Auth::login($system);


        // Default data seeders.
        $this->call([
            RbacSeeder::class,
            GroupSeeder::class,
            CertificateCategorySeeder::class,
            UserSeeder::class
        ]);

        // Random data seeders.
        $this->call([
            PersonSeeder::class,
        ]);


        // Closing seeders
        $this->call([
            OAuthSeeder::class,
        ]);

        Auth::logout();


    }
}
