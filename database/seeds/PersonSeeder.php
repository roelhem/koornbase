<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

use App\Person;
use App\Membership;

class PersonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        factory(Person::class, 100)->create()->each(function(Person $person) use ($faker) {

            factory(\App\PersonEmailAddress::class, $faker->numberBetween(0, 5))->create(['person_id' => $person->id]);
            factory(\App\PersonPhoneNumber::class, $faker->numberBetween(0, 5))->create(['person_id' => $person->id]);
            factory(\App\GroupMembership::class, $faker->numberBetween(0, 5))->create(['person_id' => $person->id]);

            // The day this person turns sixteen (and can be a member of the Koornbeurs)
            $sixteen_date = $person->getBirthDay(16);

            // Check if this person is already sixteen.
            if($sixteen_date->isPast()) {

                $params = ['person_id' => $person->id]; // Forcing params of the membership.
                if($faker->boolean) { $params['end'] = null; } // Increase the chance that the membership has not ended yet.
                factory(Membership::class)->create($params); // Create the membership
            }
        });
    }
}
