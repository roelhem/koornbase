<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

use App\Person;
use App\Membership;
use App\Enums\MembershipStatus;
use Carbon\Carbon;

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

            // ADD SOME CONTACT INFORMATION
            // Add email addresses
            factory(\App\PersonEmailAddress::class, $faker->numberBetween(0, 5))
                ->create(['person_id' => $person->id]);
            // Add phone numbers
            factory(App\PersonPhoneNumber::class, $faker->numberBetween(0, 5))
                ->create(['person_id' => $person->id]);
            // Add addresses
            factory(\App\PersonAddress::class,$faker->numberBetween(0, 5))
                ->create(['person_id' => $person->id]);



            // ADD SOME GROUPS
            // Init/debug groups
            $person->addGroups('init','random');
            // Random groups
            $groups = \App\Group::query()
                ->whereNotIn('slug', ['init','random'])
                ->inRandomOrder()
                ->limit($faker->numberBetween(0, 4))
                ->get();
            $person->addGroups(...$groups);


            // ADD AN USER
            if($faker->boolean(80)) {
                factory(\App\User::class)->create(['person_id' => $person->id]);
            }
            if($faker->boolean(5)) {
                factory(\App\User::class)->create(['person_id' => $person->id]);
            }



            // ADD A MEMBERSHIP
            // Determine the current membership status
            $membershipStatus = MembershipStatus::get( $faker->numberBetween(0, 3));
            $factory = factory(Membership::class);
            switch ($membershipStatus) {
                case MembershipStatus::OUTSIDER():
                    $factory = null;
                    break;
                case MembershipStatus::NOVICE():
                    $factory->states('novice');
                    break;
                case MembershipStatus::MEMBER():
                    $factory->states('member');
                    break;
                case MembershipStatus::FORMER_MEMBER():
                default:
                    break;
            }
            if($factory !== null) {
                $currentMembership = $factory->create(['person_id' => $person->id]);

                // Create a closed membership before this membership.
                $lowerBound = $person->getBirthDay(16);
                $upperBound = $currentMembership->application;
                while($faker->boolean && $upperBound > $lowerBound) {
                    $days = $faker->numberBetween(0, 500);
                    $endDate = (clone $upperBound)->subDays($days);

                    $oldMembership = factory(Membership::class)->create([
                        'person_id' => $person->id,
                        'end' => $endDate,
                    ]);

                    $upperBound = $oldMembership->application;
                }
            }



            // ADD SOME CERTIFICATES
            if($person->age >= 18) {
                factory(\App\Certificate::class, $faker->numberBetween(0, 3))->create(['person_id' => $person->id]);
            }



            // ADD A KOORNBEURS CARD
            // 50/50 change of a deactivated card.
            if($faker->boolean) {
                factory(\App\KoornbeursCard::class)->create(['owner_id' => $person->id]);
            }
            // Always one active card
            factory(\App\KoornbeursCard::class)->states('active')->create(['owner_id' => $person->id]);


        });
    }
}
