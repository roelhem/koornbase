<?php

use Illuminate\Database\Seeder;

class RandomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Tag::class, 20)->create();

        factory(\App\Budget::class, 20)->create();
    }
}
