<?php

use Illuminate\Database\Seeder;

use Carbon\Carbon;
use App\Person;

class RoelHemerikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws
     */
    public function run()
    {
        $person = Person::create([
            'name_first' => 'Roel',
            'name_middle' => 'Adriaan Bernard',
            'name_last' => 'Hemerik',
            'name_initials' => 'rab',
            'birth_date' => Carbon::createFromDate(1993,9,20)
        ]);

        if($person instanceof Person) {

            $user = $person->users()->create([
                'name' => 'roelhem',
                'email' => 'ik@roelweb.com',
                'password' => '$2y$10$PHXEplWPGB03vQc9x4OVu.hV4V9V3FZyk9kLBVimSFcN11etJU8Aq',
            ]);

            if($user instanceof \App\User) {
                $user->assignNode('MasterDeveloper');
                $user->assignNode('Webmaster');
            }

            $person->memberships()->create([
                'application' => Carbon::createFromDate(2013, 9, 1),
                'start' => Carbon::createFromDate(2013, 10, 1)
            ]);

            $person->addresses()->create([
                'label' => 'privé',
                'locality' => 'Delft',
                'postal_code' => '2611 EW',
                'address_line_1' => 'De Vlouw 1d',
                'locale' => 'nl_NL'
            ]);

            $person->addresses()->create([
                'label' => 'ouders',
                'locality' => 'Hazerswoude-Dorp',
                'postal_code' => '2391 EH',
                'address_line_1' => 'Jacoba van Beyerenlaan 5',
                'locale' => 'nl_NL'
            ]);

            $person->phoneNumbers()->create([
                'label' => 'privé',
                'phone_number' => '0643941490',
                'country_code' => 'NL',
            ]);

            $person->phoneNumbers()->create([
                'label' => 'huis',
                'phone_number' => '0152125844',
                'country_code' => 'NL',
            ]);

            $person->phoneNumbers()->create([
                'label' => 'ouders',
                'phone_number' => '0172587143',
                'country_code' => 'NL',
            ]);

            $person->emailAddresses()->create([
                'label' => 'privé',
                'email_address' => 'koornbeurs@roelweb.com'
            ]);

            $person->emailAddresses()->create([
                'label' => 'gmail',
                'email_address' => 'roelhemerik@gmail.com'
            ]);

            $person->addGroups('init');
            $person->addGroups('moderator','webmaster','db','server','apps', 'ontwikkelaars');
            $person->addGroups('wiskunde','tappers','geluid','redux','progki','techki');


            $person->cards()->create([
                'ref' => '15010020',
                'version' => 'v1',
                'activated_at' => Carbon::createFromDate(2015,2,14),
            ]);

            $certificateCategory = \App\CertificateCategory::where(['name_short' => 'IVA'])->first();

            if($certificateCategory instanceof \App\CertificateCategory) {
                $certificateCategory->certificates()->create([
                    'person_id' => $person->id
                ]);
            }

        }

    }
}
