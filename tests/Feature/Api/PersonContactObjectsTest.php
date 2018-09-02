<?php

namespace Tests\Feature\Api;

use App\Person;
use App\PersonAddress;
use App\PersonEmailAddress;
use App\PersonPhoneNumber;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PersonContactObjectsTest extends TestCase
{

    use RefreshDatabase;

    /**
     * Gives the different types of contact-objects
     *
     * @return array
     */
    public function typeProvider() {
        return [
            'PERSON_ADDRESS' => [
                PersonAddress::class,
                "/api/person-addresses",
                [
                    'country_code' => 'NL',
                    'address_line_1' => 'Voorbeeld-adres 12',
                    'postal_code' => '1234 AB',
                    'locality' => 'Delft'
                ]
            ],
            'PERSON_EMAIL_ADDRESS' => [
                PersonEmailAddress::class,
                "/api/person-email-addresses",
                [ 'email_address' => 'test@koornbeurs.nl' ]
            ],
            'PERSON_PHONE_NUMBER' => [
                PersonPhoneNumber::class,
                "/api/person-phone-numbers",
                [ 'phone_number' => '06 34 941 490' ]
            ]
        ];
    }

    /**
     * Tests the shared basic usage of the index endpoint
     *
     * @param string $class
     * @param string $path
     * @dataProvider typeProvider
     * @return void
     */
    public function testBasicIndex($class, $path)
    {
        $this->asSuper();

        $this->getJson($path)->assertStatus(200)
            ->assertJsonCount(0, 'data');

        factory($class, 4)->create();

        $this->getJson($path)->assertStatus(200)
            ->assertJsonCount(4, 'data');
    }

    /**
     * Tests the shared basic usage of the show endpoint
     *
     * @param string $class
     * @param string $path
     * @dataProvider typeProvider
     * @return void
     */
    public function testBasicShow($class, $path)
    {
        $this->asSuper();

        // Empty request
        $this->getJson($path."/0")->assertStatus(404);

        $model = factory($class)->create();

        // Minimal request
        $this->getJson($path."/{$model->id}")
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $model->id,
                    'label' => $model->label
                ]
            ]);

        // With relation request
        $this->getJson($path."/{$model->id}?with=person")
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $model->id,
                    'label' => $model->label,
                    'person' => [
                        'id' => $model->person_id
                    ]
                ]
            ]);
    }

    /**
     * Tests the shared basic usage of the store endpoint
     *
     * @param string $class
     * @param string $path
     * @param array $requiredValues
     * @dataProvider typeProvider
     * @return void
     */
    public function testStoreBasic($class, $path, $requiredValues)
    {
        $this->asSuper();
        $table = factory($class)->make()->getTable();

        $person = factory(Person::class)->create();

        $paramGroups = [
            'specific' => $requiredValues,
            'label' => ['label' => 'basic_store_test_label'],
            'person' => ['person' => $person->id]
        ];

        // EMPTY STORE ATTEMPT
        $this->postJson($path, [])->assertStatus(422);

        // Loop over each group
        $allParams = [];
        foreach ($paramGroups as $group => $params) {
            $allParams = $allParams + $params;

            // TRY TO ONLY GIVE EACH GROUP ITSELF
            $this->postJson($path, $params)->assertStatus(422);

            // Loop over each group again, and skip itself
            foreach ($paramGroups as $otherGroup => $otherParams) {
                if($group !== $otherGroup) {

                    // TRY EACH COMBINATION OF TWO GROUPS
                    $this->postJson($path, $params + $otherParams)->assertStatus(422);
                }
            }
        }

        // STORE WITH ALL THE REQUIRED PARAMETERS
        $this->postJson($path, $allParams)->assertStatus(201);

        // CHECK IF IT EXISTS IN THE DATABASE TABLE
        $this->assertDatabaseHas($table, ['person_id' => $person->id, 'label' => 'basic_store_test_label']);
    }

    /**
     * Tests if the store endpoint handles indexes in the right way.
     *
     * @param string $class
     * @param string $path
     * @param array $requiredValues
     * @dataProvider typeProvider
     */
    public function testStoreIndexValues($class, $path, $requiredValues) {

        // PREPARING THE TEST

        $this->asSuper();
        $table = factory($class)->make()->getTable();
        $persons = factory(Person::class, 2)->create();

        $params = function($ref, $index = false, $person = 0) use ($persons, $requiredValues) {
            $res = $requiredValues;
            $res['label'] = 'storeIndexValues_' . $ref;
            $res['person'] = $persons[$person]->id;
            if($index !== false) {
                $res['index'] = $index;
            }
            return $res;
        };

        $assertIndex = function($ref, $index, $person = 0) use ($table, $persons) {
            $this->assertDatabaseHas($table, [
                'person_id' => $persons[$person]->id,
                'label' => 'storeIndexValues_'.$ref,
                'index' => $index
            ]);
        };

        // TESTS
        $this->postJson($path, $params('A'))->assertStatus(201);
        $assertIndex('A',0);

        $this->postJson($path, $params('B'))->assertStatus(201);
        $assertIndex('A', 0);
        $assertIndex('B', 1);

        $this->postJson($path, $params('Z', false, 1))->assertStatus(201);
        $assertIndex('A', 0);
        $assertIndex('B', 1);
        $assertIndex('Z',0, 1);

        $this->postJson($path, $params('X', 0, 1))->assertStatus(201);
        $assertIndex('A', 0);
        $assertIndex('B', 1);
        $assertIndex('X', 0,1);
        $assertIndex('Z', 1,1);

        $this->postJson($path, $params('C', null))->assertStatus(201);
        $assertIndex('A', 0);
        $assertIndex('B', 1);
        $assertIndex('C', 2);

        $this->postJson($path, $params('BEFORE', -1))->assertStatus(201);
        $assertIndex('BEFORE', 0);
        $assertIndex('A', 1);
        $assertIndex('B', 2);
        $assertIndex('C', 3);

        $this->postJson($path, $params('2', 2))->assertStatus(201);
        $assertIndex('BEFORE', 0);
        $assertIndex('A', 1);
        $assertIndex('2', 2);
        $assertIndex('B', 3);
        $assertIndex('C', 4);

        $this->postJson($path, $params('AFTER', 999))->assertStatus(201);
        $assertIndex('AFTER', 5);
        $assertIndex('X', 0,1);
        $assertIndex('Z', 1,1);

    }

    /**
     * Tests if the store endpoint validates labels to make sure that every person has only contact objects with
     * unique labels.
     *
     * @param string $class
     * @param string $path
     * @param array $requiredValues
     * @dataProvider typeProvider
     */
    public function testStoreUniqueLabel($class, $path, $requiredValues) {

        // PREPARATION
        $this->asSuper();
        $persons = factory(Person::class, 2)->create();

        $params = function($label, $person = 0) use ($persons, $requiredValues) {
            $res = $requiredValues;
            $res['label'] = $label;
            $res['person'] = $persons[$person]->id;
            return $res;
        };

        // TESTS
        $this->postJson($path, $params('A'))->assertStatus(201);
        $this->postJson($path, $params('A'))->assertStatus(422);
        $this->postJson($path, $params('B'))->assertStatus(201);

        $this->postJson($path, $params('A',1))->assertStatus(201);
        $this->postJson($path, $params('A',1))->assertStatus(422);
    }

    /**
     * Tests the shared basic usage of the update endpoint
     *
     * @param string $class
     * @param string $path
     * @dataProvider typeProvider
     */
    public function testUpdateBasic($class, $path) {

        $this->asSuper();

        $this->putJson("{$path}/0", [])->assertStatus(404);

        $model = factory($class)->create();

        $this->putJson("{$path}/{$model->id}", [])->assertStatus(200);

    }

    /**
     * Tests if the update endpoint handles index values as intended
     *
     * @param string $class
     * @param string $path
     * @dataProvider typeProvider
     */
    public function testUpdateIndexValues($class, $path) {

        // PREPARATION
        $this->asSuper();

        $person = factory(Person::class)->create();
        $models = factory($class, 5)->create(['person_id' => $person->id]);
        $table = factory($class)->make()->getTable();

        $assertOrder = function(...$a) use ($table, $models) {
            foreach($a as $index => $model) {
                $this->assertDatabaseHas($table, [
                    'id' => $models[$model]->id,
                    'index' => $index
                ]);
            }
        };

        // TESTING
        $assertOrder(0,1,2,3,4);

        $this->putJson("{$path}/{$models[0]->id}", ['index' => 0])->assertStatus(200);
        $assertOrder(0,1,2,3,4);

        $this->putJson("{$path}/{$models[1]->id}", ['index' => null])->assertStatus(200);
        $assertOrder(0,1,2,3,4);

        $this->putJson("{$path}/{$models[4]->id}", ['index' => 0])->assertStatus(200);
        $assertOrder(4,0,1,2,3);

        $this->putJson("{$path}/{$models[3]->id}", ['index' => -1])->assertStatus(200);
        $assertOrder(3,4,0,1,2);

        $this->putJson("{$path}/{$models[2]->id}", ['index' => 2])->assertStatus(200);
        $assertOrder(3,4,2,0,1);

        $this->putJson("{$path}/{$models[0]->id}", ['index' => 4])->assertStatus(200);
        $assertOrder(3,4,2,1,0);

        $this->putJson("{$path}/{$models[4]->id}", ['index' => 999])->assertStatus(200);
        $assertOrder(3,2,1,0,4);

        $this->putJson("{$path}/{$models[4]->id}", ['index' => -999])->assertStatus(200);
        $assertOrder(4,3,2,1,0);

    }

    /**
     * Tests if the update endpoint handles index values as intended
     *
     * @param string $class
     * @param string $path
     * @dataProvider typeProvider
     */
    public function testUpdateUniqueLabel($class, $path) {

        $this->asSuper();

        // For multiple persons
        $persons = factory(Person::class, 2)->create();
        foreach ($persons as $person) {
            // Which have multiple models
            $models = factory($class, 3)->create(['person_id' => $person->id]);
            foreach ($models as $index => $model) {

                // CANT SET TO AN PREVIOUS LABEL
                for ($i = 0; $i < $index; $i++) {
                    $this->putJson("{$path}/{$model->id}", ['label' => "label_$i"])->assertStatus(422);
                    $this->assertDatabaseMissing($model->getTable(), [
                        'id' => $model->id,
                        'label' => "label_$i"
                    ]);
                }

                // CAN SET TO A LABEL THAT IS UNIQUE FOR THE SPECIFIC PERSON
                $this->putJson("{$path}/{$model->id}", ['label' => "label_$index"])->assertStatus(200);
                $this->assertDatabaseHas($model->getTable(), [
                    'id' => $model->id,
                    'label' => "label_$index"
                ]);
            }
        }
    }

    /**
     * Tests if the shared basic usage of the delete endpoint works properly
     *
     * @param string $class
     * @param string $path
     * @dataProvider typeProvider
     */
    public function testDeleteBasic($class, $path)
    {
        $this->asSuper();

        $this->deleteJson("{$path}/0")->assertStatus(404);

        $model = factory($class)->create();

        $this->assertDatabaseHas($model->getTable(), ['id' => $model->id]);

        $this->deleteJson("{$path}/{$model->id}")->assertStatus(200);

        $this->assertDatabaseMissing($model->getTable(), ['id' => $model->id]);
    }

    /**
     * Tests if the delete endpoint handles index values properly
     *
     * @param string $class
     * @param string $path
     * @dataProvider typeProvider
     */
    public function testDeleteIndexValues($class, $path)
    {
        $this->asSuper();

        $person = factory(Person::class)->create();
        $modelA = factory($class)->create(['person_id' => $person]);
        $modelB = factory($class)->create(['person_id' => $person]);
        $modelC = factory($class)->create(['person_id' => $person]);

        $this->assertDatabaseHas($modelA->getTable(), ['id' => $modelA->id, 'index' => 0]);
        $this->assertDatabaseHas($modelB->getTable(), ['id' => $modelB->id, 'index' => 1]);
        $this->assertDatabaseHas($modelC->getTable(), ['id' => $modelC->id, 'index' => 2]);

        $this->deleteJson("{$path}/{$modelB->id}")->assertStatus(200);

        $this->assertDatabaseHas($modelA->getTable(), ['id' => $modelA->id, 'index' => 0]);
        $this->assertDatabaseMissing($modelB->getTable(), ['id' => $modelB->id]);
        $this->assertDatabaseHas($modelC->getTable(), ['id' => $modelC->id, 'index' => 1]);
    }

}
