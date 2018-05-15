<?php

use Faker\Generator as Faker;

use App\Membership;
use App\Person;
use Carbon\Carbon;

$factory->define(Membership::class, function (Faker $faker) {

    $application = $faker->dateTimeBetween('-30 years', '+1 years');
    $start = $faker->dateTimeBetween($application, '+2 years');
    $end = $faker->dateTimeBetween($start, '+3 years');

    return [
        'person_id' => function() {
            return Person::query()->inRandomOrder()->value('id');
        },
        'application' => function($self) use ($faker) {
            $person = Person::findOrFail($self['person_id'])->first();
            $sixteen_date = $person->getBirthDay(16);

            if($sixteen_date->isPast()) {
                $days_after_sixteen = $faker->numberBetween(0, $sixteen_date->diffInDays(Carbon::today()));
            } else {
                return null;
            }

            $result = (clone $sixteen_date)->addDays($days_after_sixteen);

            if($result->isPast()) {
                return $result;
            }

            return null;
        },
        'start' => function($self) use ($faker) {
            $person = Person::findOrFail($self['person_id'])->first();
            $sixteen_date = $person->getBirthDay(16);

            if($sixteen_date->isPast()) {
                $days_after_sixteen = $faker->numberBetween(0, $sixteen_date->diffInDays(Carbon::today()));
            } else {
                return null;
            }

            $result = (clone $sixteen_date)->addDays($days_after_sixteen);
            $result->next(Carbon::WEDNESDAY);

            if($result->isPast()) {

                if($self['application'] instanceof Carbon) {
                    if($self['application'] < $result) {
                        return $result;
                    } else {
                        return null;
                    }
                } else {
                    return $result;
                }
            }

            return null;
        },
        'end' => function($self) use ($faker) {
            $person = Person::findOrFail($self['person_id'])->first();
            $sixteen_date = $person->getBirthDay(16);

            if($sixteen_date->isPast()) {
                $days_after_sixteen = $faker->numberBetween(0, $sixteen_date->diffInDays(Carbon::today()));
            } else {
                return null;
            }

            $result = (clone $sixteen_date)->addDays($days_after_sixteen);

            if($result->isPast()) {

                if($self['application'] instanceof Carbon) {
                    if($self['application'] < $result) {
                        return $result;
                    } else {
                        return null;
                    }
                }

                if($self['start'] instanceof Carbon) {
                    if($self['start'] < $result) {
                        return $result;
                    } else {
                        return null;
                    }
                }

                return $result;
            }

            return null;
        },
        'remarks' => 'Deze membership is gegenereerd op basis van random datums.'
    ];
});

$factory->state(Membership::class, 'novice', [
    'start' => null,
    'end' => null
]);

$factory->state(Membership::class, 'member', [
    'end' => null
]);