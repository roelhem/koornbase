<?php

use Illuminate\Database\Seeder;

use Carbon\Carbon;
use App\Person;
use App\User;

class RoelHemerikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $person = Person::create([
            'name_initials' => 'R.A.B.',
            'name_first' => 'Roel',
            'name_middle' => 'Adriaan Bernard',
            'name_prefix' => null,
            'name_last' => 'Hemerik',
            'name_nickname' => null,
            'birth_date' => Carbon::createFromDate(1993,9,20)
        ]);

        $user = new User([
            'name' => 'roelhem',
            'email' => 'ik@roelweb.com',
            'password' => '$2y$10$PHXEplWPGB03vQc9x4OVu.hV4V9V3FZyk9kLBVimSFcN11etJU8Aq',
        ]);

        $person->users()->save($user);

        $person->memberships()->create([
            'application' => Carbon::createFromDate(2013,9,1),
            'start' => Carbon::createFromDate(2013,10,1)
        ]);

        $person->addresses()->create([
            'label' => 'privé',
            'is_primary' => true,
            'locality' => 'Delft',
            'postal_code' => '2611 EW',
            'address_line_1' => 'De Vlouw 1d',
            'locale' => 'nl_NL'
        ]);

        $person->addresses()->create([
            'label' => 'ouders',
            'for_emergency' => true,
            'locality' => 'Hazerswoude-Dorp',
            'postal_code' => '2391 EH',
            'address_line_1' => 'Jacoba van Beyerenlaan 5',
            'locale' => 'nl_NL'
        ]);

        $person->phoneNumbers()->create([
            'label' => 'privé',
            'is_mobile' => true,
            'is_primary' => true,
            'phone_number' => '0643941490',
        ]);

        $person->phoneNumbers()->create([
            'label' => 'huis',
            'phone_number' => '0152125844'
        ]);

        $person->phoneNumbers()->create([
            'label' => 'ouders',
            'for_emergency' => true,
            'phone_number' => '0172587143'
        ]);

        $person->emailAddresses()->create([
            'label' => 'privé',
            'is_primary' => true,
            'email_address' => 'koornbeurs@roelweb.com'
        ]);

        $person->emailAddresses()->create([
            'label' => 'gmail',
            'email_address' => 'roelhemerik@gmail.com'
        ]);

        $person->groupMemberships()->create([
            'group_id' => \App\Group::findBySlugOrFail('init')->id,
        ]);

        $person->groupMemberships()->create([
            'group_id' => \App\Group::findBySlugOrFail('moderators')->id,
        ]);

        $person->groupMemberships()->create([
            'group_id' => \App\Group::findBySlugOrFail('webmasters')->id,
        ]);

        $person->groupMemberships()->create([
            'group_id' => \App\Group::findBySlugOrFail('db')->id,
        ]);

        $person->groupMemberships()->create([
            'group_id' => \App\Group::findBySlugOrFail('server')->id,
        ]);

        $person->groupMemberships()->create([
            'group_id' => \App\Group::findBySlugOrFail('apps')->id,
        ]);

        $person->groupMemberships()->create([
            'group_id' => \App\Group::findBySlugOrFail('ontwikkelaars')->id,
        ]);

        $person->groupMemberships()->create([
            'group_id' => \App\Group::findBySlugOrFail('wiskunde')->id,
            'start' => Carbon::createFromDate(2013,9,1),
        ]);

        $person->groupMemberships()->create([
            'group_id' => \App\Group::findBySlugOrFail('freaky')->id,
            'start' => Carbon::createFromDate(2013,12,1),
            'end' => Carbon::createFromDate(2016,2,1),
        ]);

        $person->groupMemberships()->create([
            'group_id' => \App\Group::findBySlugOrFail('tappers')->id,
            'start' => Carbon::createFromDate(2013,9,8),
        ]);

        $person->groupMemberships()->create([
            'group_id' => \App\Group::findBySlugOrFail('geluid')->id,
            'start' => Carbon::createFromDate(2015,9,8),
        ]);

        $person->groupMemberships()->create([
            'group_id' => \App\Group::findBySlugOrFail('newkie')->id,
            'start' => Carbon::createFromDate(2014,1,1),
            'end' => Carbon::createFromDate(2014,9,1),
        ]);

        $person->groupMemberships()->create([
            'group_id' => \App\Group::findBySlugOrFail('redux')->id,
            'start' => Carbon::createFromDate(2014,2,1),
        ]);

        $person->groupMemberships()->create([
            'group_id' => \App\Group::findBySlugOrFail('progki')->id,
            'start' => Carbon::createFromDate(2016,8,1),
        ]);

        $person->groupMemberships()->create([
            'group_id' => \App\Group::findBySlugOrFail('techkie')->id,
            'start' => Carbon::createFromDate(2019,1,1),
        ]);


        // KOORNBEURS CARD

        \App\KoornbeursCard::create(['id' => 15010020]);

        $person->cardOwnerships()->create([
            'card_id' => 15010020,
            'start' => Carbon::createFromDate(2015,10,20)
        ]);

        \App\KoornbeursCard::create(['id' => 15010054]);

    }
}
