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
            $res = Person::query()->inRandomOrder()->first();
            if($res instanceof Person) {
                return $res->id;
            } else {
                return factory(Person::class)->create()->id;
            }
        },
        'application' => function($self) use ($faker) {
            $start = array_get($self, 'start');
            $end = array_get($self, 'end');

            // Finding the lower bound
            $lowerBound = Carbon::parse('-10 years');

            // Finding the upper bound for the application
            if($start instanceof Carbon) {
                $upperBound = $start;
            } elseif($start instanceof DateTime) {
                $upperBound = Carbon::instance($start);
            } elseif(is_string($start)) {
                $upperBound = Carbon::parse($start);
            } elseif($end instanceof Carbon) {
                $upperBound = $end;
            } elseif($end instanceof DateTime) {
                $upperBound = Carbon::instance($end);
            } elseif(is_string($end)) {
                $upperBound = Carbon::parse($end);
            } else {
                $upperBound = Carbon::now();
            }

            // Assert that the lower bound is always smaller than the upper bound.
            while($lowerBound >= $upperBound) {
                $lowerBound->subMonth();
            }

            // Pick a random date between the upper and the lower bound.
            $randomDays = $faker->numberBetween(0, $lowerBound->diffInDays($upperBound));
            return (clone $lowerBound)->addDays($randomDays);

        },
        'start' => function($self) use ($faker) {

            $application = array_get($self, 'application');
            $end = array_get($self, 'end');

            // Finding the lower bound
            if($application instanceof Carbon) {
                $lowerBound = $application;
            } elseif($application instanceof DateTime) {
                $lowerBound = Carbon::instance($application);
            } elseif(is_string($application)) {
                $lowerBound = Carbon::parse($application);
            } else {
                $lowerBound = Carbon::parse('-10 years');
            }

            // Finding the upper bound
            if($end instanceof Carbon) {
                $upperBound = $end;
            } elseif($end instanceof DateTime) {
                $upperBound = Carbon::instance($end);
            } elseif(is_string($end)) {
                $upperBound = Carbon::parse($end);
            } else {
                $upperBound = Carbon::now();
            }

            // Pick a random date between the upper and the lower bound.
            $randomDays = $faker->numberBetween(0, $lowerBound->diffInDays($upperBound));
            return (clone $lowerBound)->addDays($randomDays);

        },
        'end' => function($self) use ($faker) {
            $application = array_get($self, 'application');
            $start = array_get($self, 'start');

            // Finding the lower bound
            if($start instanceof Carbon) {
                $lowerBound = $start;
            } elseif($start instanceof DateTime) {
                $lowerBound = Carbon::instance($start);
            } elseif(is_string($start)) {
                $lowerBound = Carbon::parse($start);
            } elseif($application instanceof Carbon) {
                $lowerBound = $application;
            } elseif($application instanceof DateTime) {
                $lowerBound = Carbon::instance($application);
            } elseif(is_string($application)) {
                $lowerBound = Carbon::parse($application);
            } else {
                $lowerBound = Carbon::parse('-10 years');
            }

            // Finding the upper bound
            $upperBound = Carbon::now();

            // Assert the upper bound is higher than the lower bound
            while($upperBound <= $lowerBound) {
                $upperBound->addMonth();
            }

            // Pick a random date between the upper and the lower bound.
            $randomDays = $faker->numberBetween(0, $lowerBound->diffInDays($upperBound));
            return (clone $lowerBound)->addDays($randomDays);
        },
        'remarks' => 'Deze membership is gegenereerd op basis van random data. Gebruik deze membership alleen voor test-toepassingen.'
    ];
});

$factory->state(Membership::class, 'novice', [
    'start' => null,
    'end' => null
]);

$factory->state(Membership::class, 'member', [
    'end' => null
]);