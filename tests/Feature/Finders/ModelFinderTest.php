<?php

namespace Tests\Feature;

use App\Contracts\Finders\FinderCollection;
use App\Debtor;
use App\Group;
use App\GroupCategory;
use App\Services\Finders\FindableValidator;
use App\Services\Finders\GroupCategoryFinder;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ModelFinderTest extends TestCase
{

    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGroupCategoryFinder()
    {
        $finder = resolve(GroupCategoryFinder::class);

        $this->assertEquals(GroupCategory::class, $finder->modelClass());

        $this->assertTrue($finder->accepts(1));
        $this->assertFalse($finder->accepts([]));

        $categoryA = factory(GroupCategory::class)->create();
        $categoryB = factory(GroupCategory::class)->create();

        $idA = $categoryA->id;
        $slugB = $categoryB->slug;

        $foundCategoryA = $finder->find($idA);
        $foundCategoryB = $finder->find($slugB);

        $this->assertInstanceOf(GroupCategory::class, $foundCategoryA);
        $this->assertInstanceOf(GroupCategory::class, $foundCategoryB);

        $this->assertEquals($categoryA->id, $foundCategoryA->id);
        $this->assertEquals($categoryB->id, $foundCategoryB->id);
    }

    public function testFinderCollection() {
        $collection = resolve(FinderCollection::class);

        $this->assertTrue($collection->canFind(GroupCategory::class));
        $this->assertTrue($collection->canFind(Group::class));
        $this->assertFalse($collection->canFind('a'));
        $this->assertFalse($collection->canFind(Debtor::class));
    }

    public function testFindsRule() {
        $categoryA = factory(GroupCategory::class)->create();
        $categoryB = factory(GroupCategory::class)->create();

        $idA = $categoryA->id;
        $slugB = $categoryB->slug;

        $validatorA = \Validator::make(['a' => $idA, 'b' => $slugB, 'c' => 200], [
            'a' => 'finds:App\GroupCategory',
            'b' => 'finds:App\GroupCategory'
        ]);

        $this->assertFalse($validatorA->fails());

        $validatorB = \Validator::make(['a' => $idA, 'b' => $slugB, 'c' => 200], [
            'a' => 'finds:App\GroupCategory',
            'b' => 'finds:App\GroupCategory',
            'c' => 'finds:App\GroupCategory',
        ]);

        $this->assertTrue($validatorB->fails());


    }
}
