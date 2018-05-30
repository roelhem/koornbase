<?php

use Illuminate\Database\Seeder;
use App\GroupCategory;
use App\Group;
use App\GroupTitle;
use App\Enums\GroupCategoryStyles;

class GroupSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        foreach ($this->defaultValues() as $cat) {
            $category = GroupCategory::create(array_except($cat, ['groups','roles']));

            foreach (array_get($cat, 'roles',[]) as $role) {
                $category->assignRole($role);
            }

            foreach (array_get($cat, 'groups',[]) as $grp) {
                $group = Group::make(array_except($grp,['titles','roles']));
                $category->groups()->save($group);

                foreach (array_get($grp, 'roles',[]) as $role) {
                    $group->assignRole($role);
                }
            }
        }

    }

    /**
     * Returns an array that contains the default seeding values.
     *
     * @return array
     */
    public function defaultValues() {
        return [
            [
                'name' => 'KoornBase-systeem gerelateerd',
                'name_short' => 'Systeem',
                'description' => "Groepen die alleen functies hebben binnen het KoornBase-systeem of zeer belangrijk zijn om de KoornBase goed te laten werken.",
                'style' => 'group-system',
                'options' => [
                    'showOnPersonsPage' => false
                ],
                'is_required' => true,
                'groups' => [
                    [
                        'name' => 'Moderators',
                        'member_name' => 'Moderator',
                        'is_required' => true,
                        'roles' => ['moderator']
                    ]
                ]
            ],
            [
                'name' => 'KoornBase-systeem beheer',
                'name_short' => 'Beheer',
                'description' => 'Groepen die te maken hebben met het beheren van het KoornBase systeem.',
                'style' => 'group-master',
                'options' => [
                    'showOnPersonsPage' => false
                ],
                'is_required' => true,
                'groups' => [
                    [
                        'name' => 'Webmasters',
                        'member_name' => 'Webmaster',
                        'is_required' => true
                    ],
                    [
                        'name' => 'Databasebeheerders',
                        'name_short' => 'DB',
                        'member_name' => 'DB Admin',
                        'is_required' => true,
                    ],
                    [
                        'name' => 'Serverbeheerders',
                        'name_short' => 'Server',
                        'member_name' => 'Server Admin',
                        'is_required' => true,
                    ],
                    [
                        'name' => 'Applicatiebeheerders',
                        'name_short' => 'Apps',
                        'member_name' => 'Apps Admin',
                        'is_required' => true,
                    ]
                ]
            ],
            [
                'name' => 'Groepen om te testen.',
                'name_short' => 'Testgroepen',
                'description' => 'Groepen die nodig zijn om de KoornBase goed te testen.',
                'style' => 'group-debug',
                'options' => [
                    'showOnPersonsPage' => false
                ],
                'is_required' => true,
                'groups' => [
                    [
                        'name' => 'Bij Initialisatie Gegenereerd',
                        'name_short' => 'Init',
                        'description' => 'De personen die bij de initialisatie van de KoornBase een belangrijke rol spelen.',
                        'is_required' => true,
                    ],
                    [
                        'name' => 'Random Gegenereerd',
                        'name_short' => 'Random',
                        'description' => 'De personen in deze groep zijn gegenereerd op basis van willekeurige data',
                        'is_required' => true,
                    ]
                ],
            ],
            [
                'name' => 'Ontwikkelaars, Testers, etc.',
                'name_short' => 'Ontwikkelaars',
                'description' => 'Groepen die nieuwe dingen ontwikkelen of testen voor de KoornBase, of voor een andere applicatie die gebruik maakt van de KoornBase.',
                'style' => 'group-develop',
                'is_required' => true,
                'groups' => [
                    [
                        'name' => 'KoornBase Hoofd-ontwikkelaars',
                        'name_short' => 'Ontwikkelaars',
                        'member_name' => 'Ontwikkelaar',
                        'description' => 'De groep die zich bezig houd met nieuwe dingen ontwikkelen voor op de KoornBase-server zelf.',
                        'is_required' => true,
                        'roles' => ['developer']
                    ],
                    [
                        'slug' => 'testers',
                        'name' => 'KoornBase-server Testers',
                        'name_short' => 'Testers',
                        'member_name' => 'Tester',
                        'description' => 'Een groep die de (nieuwe) functies van de KoornBase-server test.',
                        'is_required' => true
                    ]
                ]
            ],
            [
                'name' => 'Koornbeurs-besturen',
                'name_short' => 'Bestuur',
                'description' => 'De groepen die voor de Koornbeurs-besturen zijn bedoeld.',
                'style' => 'group-structure',
                'is_required' => true,
            ],
            [
                'name' => 'Primaire commissie',
                'description' => 'Comissies met een primaire taak waar de Koornbeurs zeer vanaf hangt. Commissies in deze categorie hebben meestal een naam die eindigd op \'-kie\' (een extra -e op het eind)',
                'style' => 'group-primary',
                'groups' => [
                    [
                        'name' => 'TapKie',
                        'description' => 'De commissie die verantwoordelijk is voor het onderhouden van de kelderbar.',
                    ],
                    [
                        'name' => 'TechKie',
                        'description' => 'De commissie die verantwoordelijk is voor het onderhouden van de licht- en geluidsapparatuur.',
                    ],
                    [
                        'name' => 'HamerEnSponsKie',
                    ],
                    [
                        'name' => 'NewKie',
                    ],
                    [
                        'name' => 'IntroducKie',
                    ]
                ],
                'roles' => ['commissielid']
            ],
            [
                'name' => 'Secundaire commissie',
                'description' => 'Comissies met een secundaire taak in de Koornbeurs. Commissies in deze categorie hebben meestal een naam die eindigd op \'-ki\' (zonder een extra -e op het eind)',
                'style' => 'group-secondary',
                'groups' => [
                    [
                        'name' => 'Freaky',
                        'description' => 'De oude commissie die verantwoordelijk was voor de bandjes in de Koornbeurs.'
                    ],
                    [
                        'name' => 'ProgKi',
                        'description' => 'De commissie die verantwoordelijk is voor de programmering van externe artiesten in de Koornbeurs.',
                        'titles' => [
                            [
                                'name' => 'Voorzitter'
                            ],
                            [
                                'name' => 'Penningmeester'
                            ],
                            [
                                'name' => 'Secretaris'
                            ]
                        ]
                    ],
                    [
                        'name' => 'PromoKi',
                    ],
                    [
                        'name' => 'GeeKi',
                    ]
                ],
                'roles' => ['commissielid']
            ],
            [
                'name' => 'Disputen',
                'name_short' => 'Dispuut',
                'style' => 'group-friend',
                'groups' => [
                    [
                        'name' => 'Dispuut Redux',
                        'name_short' => 'Redux',
                        'description' => 'Het oudste dispuut van de Koornbeurs.',
                        'member_name' => 'Redux-lid',
                        'titles' => [
                            [
                                'name' => 'Preases der Dispuut Redux',
                                'name_short' => 'Preases',
                                'max_persons' => 1,
                            ],
                            [
                                'name' => 'Questor der Dispuut Redux',
                                'name_short' => 'Questor',
                                'max_persons' => 1,
                            ]
                        ]
                    ]
                ]
            ],
            [
                'name' => 'Personeelsgroepen',
                'name_short' => 'Personeel',
                'style' => 'group-skill',
                'groups' => [
                    [
                        'name' => 'Tappers',
                        'member_name' => 'Tapper',
                        'description' => 'De leden die achter de kelderbar kunnen/mogen staan.'
                    ],
                    [
                        'name' => 'Schenkers',
                        'member_name' => 'Schenker',
                        'description' => 'De leden die achter de zolderbar kunnen/mogen staan.'
                    ],
                    [
                        'name' => 'Schoonmakers',
                        'member_name' => 'Schoonmaker',
                        'description' => 'De leden die de Koornbeurs kunnen/mogen schoonmaken.'
                    ],
                    [
                        'name' => 'Geluidstechnici',
                        'name_short' => 'Geluid',
                        'member_name' => 'Geluidstechnicus',
                        'description' => 'De leden die verstand hebben van geluid in de Koornbeurs.'
                    ],
                    [
                        'name' => 'Lichttechnici',
                        'name_short' => 'Licht',
                        'member_name' => 'Lichttechnicus',
                        'description' => 'De leden die verstand hebben van de feestverlichting in de Koornbeurs.'
                    ]
                ]
            ],
            [
                'name' => 'Studies',
                'name_short' => 'Studies',
                'style' => 'group-study',
                'groups' => [
                    [
                        'name' => 'Technische Wiskunde',
                        'name_short' => 'Wiskunde',
                        'member_name' => 'Wiskundestudent',
                    ]
                ]
            ]
        ];
    }
}
