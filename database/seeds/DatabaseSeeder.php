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

        // Default data seeders.
        $this->call([
            RbacSeeder::class,
            GroupSeeder::class,
            UserSeeder::class
        ]);

        // Data of Roel Hemerik
        $this->call([
            RoelHemerikSeeder::class
        ]);

        // Random data seeders.
        $this->call([
            PersonSeeder::class,
        ]);


    }
}
