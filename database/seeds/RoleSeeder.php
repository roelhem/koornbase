<?php

use Illuminate\Database\Seeder;
use App\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'id' => 'admin',
            'name' => 'Admin',
            'is_required' => true,
            'for_user' => true,
        ]);

        Role::create([
            'id' => 'guest',
            'name' => 'Guest',
            'is_required' => true,
        ]);

        Role::create([
            'id' => 'user',
            'name' => 'User',
            'is_required' => true,
        ]);

        Role::create([
            'id' => 'moderator',
            'name' => 'Moderator',
            'is_required' => true,
            'for_user' => true,
            'for_group' => true
        ]);

        Role::create([
            'id' => 'developer',
            'name' => 'Developer',
            'is_required' => true,
            'for_user' => true,
            'for_group' => true
        ]);

        Role::create([
            'id' => 'kennismaker',
            'name' => 'Kennismaker',
            'description' => 'Een Kennismaker, bepaald met behulp van de Memberships van een Person.',
            'is_required' => true,
        ]);

        Role::create([
            'id' => 'lid',
            'name' => 'Lid',
            'description' => 'Een Lid van de Koornbeurs, bepaald met behulp van de Memberships van een Person.',
            'is_required' => true,
        ]);

        Role::create([
            'id' => 'oud-lid',
            'name' => 'Oud-Lid',
            'description' => 'Een Oud-Lid van de Koornbeurs, bepaald met behulp van de Memberships van een Person.',
            'is_required' => true,
        ]);

        Role::create([
            'id' => 'commissielid',
            'name' => 'Commissielid',
            'for_group' => true,
            'for_group_category' => true
        ]);
    }
}
