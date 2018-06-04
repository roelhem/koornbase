<?php

namespace Tests\Feature;

use App\Person;
use App\Services\Sorters\PersonSorter;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SorterTest extends TestCase
{

    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testListAndHas()
    {
        $personSorter = new PersonSorter;

        $list = $personSorter->list();

        $this->assertContains('name-first', $list);
        $this->assertContains('name-last', $list);
        $this->assertNotContains('does-not-exist', $list);

        foreach ($list as $item) {
            $this->assertTrue($personSorter->has($item));
        }

        $this->assertFalse($personSorter->has('does-not-exist'));
    }

    public function testPersonSorting() {
        $personSorter = new PersonSorter;

        $personAA = factory(Person::class)->create(['name_first' => 'AA', 'name_last' => 'ZZ', 'name_prefix' => null]);
        $personAB = factory(Person::class)->create(['name_first' => 'AB', 'name_last' => 'ZZ', 'name_prefix' => null]);
        $personBA = factory(Person::class)->create(['name_first' => 'BA', 'name_last' => 'XX', 'name_prefix' => null]);
        $personBB = factory(Person::class)->create(['name_first' => 'BB', 'name_last' => 'XX', 'name_prefix' => null]);

        $query = $personSorter->add(Person::query(), 'name-first');
        $persons = $query->get();
        $this->assertEquals($personAA->id, $persons[0]->id);
        $this->assertEquals($personAB->id, $persons[1]->id);
        $this->assertEquals($personBA->id, $persons[2]->id);
        $this->assertEquals($personBB->id, $persons[3]->id);

        $query = $personSorter->add(Person::query(), 'name-first', true);
        $persons = $query->get();
        $this->assertEquals($personBB->id, $persons[0]->id);
        $this->assertEquals($personBA->id, $persons[1]->id);
        $this->assertEquals($personAB->id, $persons[2]->id);
        $this->assertEquals($personAA->id, $persons[3]->id);

        $query = $personSorter->addList(Person::query(), [
            'name-last',
            'name-first:asc'
        ]);
        $persons = $query->get();
        $this->assertEquals($personBA->id, $persons[0]->id);
        $this->assertEquals($personBB->id, $persons[1]->id);
        $this->assertEquals($personAA->id, $persons[2]->id);
        $this->assertEquals($personAB->id, $persons[3]->id);

        $query = $personSorter->addList(Person::query(), [
            'name-last' => 'desc',
            'name-first:desc'
        ]);
        $persons = $query->get();
        $this->assertEquals($personAB->id, $persons[0]->id);
        $this->assertEquals($personAA->id, $persons[1]->id);
        $this->assertEquals($personBB->id, $persons[2]->id);
        $this->assertEquals($personBA->id, $persons[3]->id);
    }
}
