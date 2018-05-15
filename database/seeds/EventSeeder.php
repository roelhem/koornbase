<?php

use Illuminate\Database\Seeder;

use Faker\Generator as Faker;
use Carbon\Carbon;
use App\Event;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {

        // PROGKI-VERGADERINGEN

        $progkiVergaderingDefault = [
            'category_id' => 'vergadering',
            'name' => 'Progkivergadering',
            'description' => 'Een reguliere Progkivergadering',
        ];

        $tapkieVergaderingDefault = [
            'category_id' => 'vergadering',
            'name' => 'Tapkievergadering',
            'description' => 'Een reguliere Tapkievergadering'
        ];

        $ledenavondDefault = [
            'category_id' => 'ledenavond',
            'name' => 'Ledenavond',
            'description' => 'De gebruikelijke borrel op woensdag, waarbij alleen de leven van de Koornbeurs welkom zijn.'
        ];

        $firstDate = Carbon::createFromDate(2017,8,24)->next(Carbon::MONDAY);

        $lastDate = Carbon::now()->addYears(2);

        $pointer = (clone $firstDate);
        $alsoTapkie = true;
        while ($pointer < $lastDate) {
            if($faker->boolean(10)) {
                $progkiDate = (clone $pointer)->addDay();
            } else {
                $progkiDate = $pointer;
            }

            if($faker->boolean(60)) {
                $start = (clone $progkiDate)->setTime($faker->numberBetween(19, 21), $faker->numberBetween(0, 3) * 15, 0);
                $end = (clone $start)->addMinutes($faker->numberBetween(1, 10) * 15);
                Event::create($progkiVergaderingDefault + ['start' => $start, 'end' => $end]);
            }

            if($alsoTapkie) {
                $tapkieStart = (clone $pointer)->next(Carbon::TUESDAY)
                                ->setTime($faker->numberBetween(20, 22), $faker->numberBetween(0, 3) * 15, 0);
                $tapkieEnd = (clone $tapkieStart)->addMinutes($faker->numberBetween(8, 20) * 15);

                Event::create($tapkieVergaderingDefault + ['start' => $tapkieStart, 'end' => $tapkieEnd]);

                $alsoTapkie = false;
            } elseif($faker->boolean(90)) {
                $alsoTapkie = true;
            }

            $ledenavondStart = (clone $pointer)->next(Carbon::WEDNESDAY)->setTime(21,00,00);
            $ledenavondEnd = (clone $ledenavondStart)->addMinutes($faker->numberBetween(12,30) * 15);
            Event::create($ledenavondDefault + ['start' => $ledenavondStart, 'end' => $ledenavondEnd]);

            $pointer = (clone $pointer)->addWeek();
        }




        // KELDERJAM

        $kelderjamDefault = [
            'category_id' => 'bandavond',
            'name' => 'Kelderjam',
            'description' => 'De open jam-avond van de Koornbeurs.',
            'is_open' => true,
        ];

        $pointer = (clone $firstDate)->nthOfMonth(2, Carbon::FRIDAY);
        while ($pointer < $lastDate) {
            $start = (clone $pointer)->setTime($faker->numberBetween(19,21), $faker->numberBetween(0, 1) * 30, 0);
            $end = (clone $pointer)->addDay()->setTime(1, 0, 0);
            Event::create($kelderjamDefault + ['start' => $start, 'end' => $end]);

            $pointer->addMonth();
            $pointer->nthOfMonth(2, Carbon::FRIDAY);
        }

        // METALCAFÉ

        $metalCafeDefault = [
            'category_id' => 'bandavond',
            'name' => 'Metalcafé',
            'description' => 'O.J.V. de Koornbeurs Metalcafé, met Livemuziek en DJ\'s.',
            'is_open' => true,
        ];

        $pointer = (clone $firstDate)->nthOfMonth(2, Carbon::SATURDAY);
        while ($pointer < $lastDate) {
            $start = (clone $pointer)->setTime(22, $faker->numberBetween(0, 1) * 30, 0);
            $end = (clone $pointer)->addDay()->setTime($faker->numberBetween(3,5), 0, 0);
            Event::create($metalCafeDefault + ['start' => $start, 'end' => $end]);

            $pointer->addMonth();
            $pointer->nthOfMonth(2, Carbon::SATURDAY);
        }

        // KELDERJAM

        $geslotenBorrelDefault = [
            'category_id' => 'gesloten-feest',
            'name' => 'Gesloten Borrel',
            'description' => 'Van een andere studentenvereniging bijvoorbeeld.',
            'is_open' => true,
        ];

        $ledenFeestDefault = [
            'category_id' => 'leden-feest',
            'name' => 'Feest voor leden',
            'description' => 'Vooral op Koornbeurs-leden gefocust.',
            'is_open' => true,
        ];

        $pointer = (clone $firstDate)->nthOfMonth(1, Carbon::FRIDAY);
        while ($pointer < $lastDate) {
            $start = (clone $pointer)->setTime($faker->numberBetween(19,21), $faker->numberBetween(0, 1) * 30, 0);
            $end = (clone $pointer)->addDay()->setTime($faker->numberBetween(1,4), 0, 0);

            if($faker->boolean) {
                Event::create($geslotenBorrelDefault + ['start' => $start, 'end' => $end]);
            } else {
                Event::create($ledenFeestDefault + ['start' => $start, 'end' => $end]);
            }

            $pointer->addMonth();
            $pointer->nthOfMonth(1, Carbon::FRIDAY);
        }



        // HERMAN

        $hermanDefault = [
            'category_id' => 'open-feest',
            'name' => 'DIXXO-Herman',
            'description' => 'Al sinds de jaren 80 het bekendste feest op nieuwjaarsdag in Delft.',
            'is_open' => true
        ];

        $firstDate = Carbon::createFromDate(1998, 1, 1);
        $lastDate = Carbon::createFromDate(2020, 1, 1);
        $pointer = $firstDate;
        while ($pointer < $lastDate) {
            $date = clone $pointer;

            $start = (clone $date)->setTime(21,00,00);
            $end = (clone $start)->addHour($faker->numberBetween(7,9));
            Event::create($hermanDefault + ['start' => $start, 'end' => $end]);

            $pointer = (clone $pointer)->addYearNoOverflow();
        }




        // SAMPLE FOR EVERY CATEGORY
        $pointer = Carbon::now()->next(Carbon::MONDAY)->setTime(10, 0,0);
        foreach (\App\EventCategory::all() as $category) {
            $start = (clone $pointer);
            $end = (clone $start)->addHours(8);

            Event::create([
                'category_id' => $category->id,
                'manager_id' => 1,
                'name' => $category->name_short.'?',
                'description' => "Een evenement om de kalenderstijl te vergelijken voor alle EventCategories. Dit event vertegenwoordigd de '{$category->name}'-categorie. \n {$category->description}",
                'start' => $start,
                'end' => $end,
                'remarks' => 'Dit evenement is automatisch aangemaakt voor ontwikkeldoeleinden.'
            ]);


            $pointer->addDay();
        }


    }
}
