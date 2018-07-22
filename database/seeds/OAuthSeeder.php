<?php

use Illuminate\Database\Seeder;

class OAuthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        \App\OAuth\App::create([
            'name' => 'KoornBase Console',
            'name_short' => 'KB Console',
            'description' => 'Een app waarmee de KoornBase in de gaten gehouden kan worden.'
        ]);

    }
}
