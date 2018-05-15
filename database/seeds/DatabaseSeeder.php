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
            RoleSeeder::class,
            PermissionSeeder::class,
            GroupSeeder::class,
            EventCategorySeeder::class,
            UserSeeder::class
        ]);

        // Data of Roel Hemerik
        $this->call([
            RoelHemerikSeeder::class
        ]);

        // Random data seeders.
        $this->call([
            RandomSeeder::class,
            PersonSeeder::class,
            EventSeeder::class,
        ]);


    }
}
