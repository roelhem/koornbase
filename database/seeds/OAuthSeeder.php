<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class OAuthSeeder extends Seeder
{


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(\Laravel\Passport\ClientRepository $clients, Faker $faker)
    {

        // Get the system user.
        $systemUser = \App\User::query()->where('name','=','system')->firstOrFail();

        // Create the main Personal Access Client.
        $clients->createPersonalAccessClient(
            $systemUser->id,
            'Main Personal Access Client',
            config('app.url').'/internal-callback'
        );

        // Create the main Password Grant Client.
        $clients->createPasswordGrantClient(
            $systemUser->id,
            'Main Password Access Client',
            config('app.url').'/internal-callback'
        );


        // Create some random clients.
        for ($i = 0; $i < $faker->numberBetween(20,30); $i++) {

            $randomUserId = \App\User::query()->inRandomOrder()->value('id');

            $name = $faker->company.' '.$faker->randomElement(['app','sharing client','devteam','mailserver','RADIX']);


            $url = 'http';
            if($faker->boolean(40)) { $url .= 's'; }
            $url .= '://'.$faker->domainName.'/';
            $url .= $faker->randomElement(['services/','app/','social/','oauth/','','','']);
            $url .= $faker->randomElement(['koornbase/','oauth/','koornbeurs/','','','']);
            $url .= $faker->randomElement(['callback','response','oauth-callback','handle.php','','']);

            $client = $clients->create($randomUserId, $name, $url);


            if($faker->boolean(5)) {
                $clients->delete($client);
            }

        }


    }
}
