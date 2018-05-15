<?php

use Illuminate\Database\Seeder;
use App\EventCategory;

class EventCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EventCategory::create([
            'id' => 'system',
            'name' => 'Systeemgerelateerde gebeurtenissen',
            'name_short' => 'Systeem',
            'description' => 'Evenementen die bij deze categorie horen hebben te maken met het onderhouden van het KoornBase-systeem.',
            'is_public' => false,
            'visibility' => 1,
            'options' => [
                'icons' => [
                    'fa' => 'server'
                ],
                'iconColor' => \App\Enums\BootstrapColors::DarkGray,
                'color' => \App\Enums\BootstrapColors::DarkGray,
            ],
            'is_required' => true
        ]);

        EventCategory::create([
            'id' => 'default',
            'name' => 'Gewone Mensa/Lange-wacht etc.',
            'name_short' => 'Kelderbar',
            'description' => 'Evenementen die standaard worden gegeven als er geen andere dingen worden georganiseerd.',
            'is_public' => true,
            'visibility' => 1,
            'options' => [
                'icons' => [
                    'fa' => 'beer'
                ],
                'iconColor' => \App\Enums\BootstrapColors::Gray,
                'color' => \App\Enums\BootstrapColors::Gray,
            ],
            'is_required' => true
        ]);

        EventCategory::create([
            'name' => 'Gewone Theezolderactiviteiten',
            'name_short' => 'Theezolder',
            'description' => 'De zolderbar en andere rustige evenementen voor leden.',
            'is_public' => false,
            'visibility' => 1,
            'options' => [
                'icons' => [
                    'fa' => 'coffee',
                ],
                'iconColor' => \App\Enums\BootstrapColors::Gray,
                'color' => \App\Enums\BootstrapColors::Gray,
            ]
        ]);

        EventCategory::create([
            'name' => 'Ledenavond',
            'description' => 'De borrelavond op de Koornbeurs, waarbij alleen leden aanwezig mogen zijn.',
            'is_public' => false,
            'visibility' => 1,
            'options' => [
                'icons' => [
                    'fa' => 'bomb'
                ],
                'iconColor' => \App\Enums\BootstrapColors::Purple,
                'color' => \App\Enums\BootstrapColors::Purple,
            ],
        ]);

        EventCategory::create([
            'name' => 'Open Feest',
            'description' => 'Feesten die open zijn voor bezoekers.',
            'is_public' => true,
            'visibility' => 20,
            'options' => [
                'icons' => [
                    'fa' => 'star'
                ],
                'iconColor' => \App\Enums\BootstrapColors::Blue,
                'color' => \App\Enums\BootstrapColors::Blue,
            ],
        ]);

        EventCategory::create([
            'name' => 'Alleen voor leden Feest',
            'name_short' => 'Leden Feest',
            'description' => 'Feesten die alleen voor leden en geintroduceerden zijn.',
            'is_public' => false,
            'visibility' => 1,
            'options' => [
                'icons' => [
                    'fa' => 'heart',
                ],
                'iconColor' => \App\Enums\BootstrapColors::Teal,
                'color' => \App\Enums\BootstrapColors::Teal,
            ],
        ]);

        EventCategory::create([
            'name' => 'Gesloten Feest',
            'description' => 'Feesten die alleen voor leden en geintroduceerden zijn.',
            'is_public' => false,
            'visibility' => 1,
            'options' => [
                'icons' => [
                    'fa' => 'shield',
                ],
                'iconColor' => \App\Enums\BootstrapColors::Azure,
                'color' => \App\Enums\BootstrapColors::Azure,
            ],
        ]);

        EventCategory::create([
            'name' => 'Live-muziek Avond',
            'name_short' => 'Bandavond',
            'description' => 'Evenementen waarbij livemuziek wordt gespeeld.',
            'is_public' => null,
            'visibility' => 20,
            'options' => [
                'icons' => [
                    'fa' => 'music',
                ],
                'iconColor' => \App\Enums\BootstrapColors::Cyan,
                'color' => \App\Enums\BootstrapColors::Cyan,
            ],
        ]);

        EventCategory::create([
            'name' => 'Commissievergadering',
            'name_short' => 'Vergadering',
            'description' => 'Bijeenkomsten van bijvoorbeeld commissies of werkgroepen.',
            'is_public' => false,
            'visibility' => 1,
            'options' => [
                'icons' => [
                    'fa' => 'legal',
                ],
                'iconColor' => \App\Enums\BootstrapColors::Yellow,
                'color' => \App\Enums\BootstrapColors::Yellow,
            ],
        ]);

        EventCategory::create([
            'name' => 'Ledenvergadering',
            'description' => 'Een vergadering onder alle leden waar belangrijke besluiten genomen worden.',
            'is_public' => false,
            'visibility' => 1,
            'options' => [
                'icons' => [
                    'fa' => 'bank',
                ],
                'iconColor' => \App\Enums\BootstrapColors::Orange,
                'color' => \App\Enums\BootstrapColors::Orange,
            ],
        ]);

        EventCategory::create([
            'name' => 'Diner, Eettafel, Barbeque, etc.',
            'name_short' => 'Voedsel',
            'description' => 'De evenementen voor leden waarbij er samen gegeten wordt.',
            'is_public' => false,
            'visibility' => 1,
            'options' => [
                'icons' => [
                    'fa' => 'cutlery',
                ],
                'iconColor' => \App\Enums\BootstrapColors::Lime,
                'color' => \App\Enums\BootstrapColors::Lime,
            ]
        ]);

        EventCategory::create([
            'name' => 'Sportactiviteit',
            'name_short' => 'Sport',
            'description' => 'Sportactiveiten die vanuit de Koornbeurs georganiseerd worden.',
            'is_public' => false,
            'visibility' => 1,
            'options' => [
                'icons' => [
                    'fa' => 'futbol-o',
                ],
                'iconColor' => \App\Enums\BootstrapColors::Green,
                'color' => \App\Enums\BootstrapColors::Green,
            ]
        ]);

        EventCategory::create([
            'name' => 'Koornbeurs-reis',
            'name_short' => 'Reis',
            'description' => 'Een reis waarbij veel leden van de Koornbeurs mee gaan.',
            'is_public' => false,
            'visibility' => 1,
            'options' => [
                'icons' => [
                    'fa' => 'compass',
                ],
                'iconColor' => \App\Enums\BootstrapColors::Green,
                'color' => \App\Enums\BootstrapColors::Gray,
            ]
        ]);

        EventCategory::create([
            'name' => 'Klusmomenten',
            'name_short' => 'Klussen',
            'description' => 'Een evenement waarbij de leden kunnen helpen met klussen.',
            'is_public' => false,
            'visibility' => 1,
            'options' => [
                'icons' => [
                    'fa' => 'wrench',
                ],
                'iconColor' => \App\Enums\BootstrapColors::DarkGray,
                'color' => \App\Enums\BootstrapColors::Red,
            ]
        ]);


    }
}
