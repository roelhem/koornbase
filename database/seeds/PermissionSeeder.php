<?php

use Illuminate\Database\Seeder;

use App\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'id' => 'permissions:view-all',
            'name' => 'See all the permissions',
        ])->assignToRole('admin');
    }
}
