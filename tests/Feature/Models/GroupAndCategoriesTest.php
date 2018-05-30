<?php

namespace Tests\Feature\Models;

use App\Group;
use App\GroupCategory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GroupAndCategoriesTest extends TestCase
{

    use RefreshDatabase;

    /**
     * Data provider for testing GroupCategory in the database.
     *
     * @return array
     */
    public function groupCategoryProvider() {
        return [
            'MINIMUM' => [
                [
                    'name' => 'Minimum Test Category'
                ],
                [
                    'name' => 'Minimum Test Category',
                    'name_short' => null,
                    'slug' => 'minimum-test-category',
                    'description' => null,
                    'style' => null,
                    'is_required' => false,
                    'options' => '{}',
                ]
            ],
            'SIMPLE_BUT_MORE' => [
                [
                    'name' => 'Simple But More Test Category',
                    'name_short' => 'simp. test',
                    'description' => 'Een simpele test categorie met iets meer informatie.',
                    'style' => 'person-default',
                    'is_required' => true,
                ],
                [
                    'name' => 'Simple But More Test Category',
                    'name_short' => 'simp. test',
                    'slug' => 'simp-test',
                    'description' => 'Een simpele test categorie met iets meer informatie.',
                    'style' => 'person-default',
                    'is_required' => true,
                    'options' => '{}',
                ]
            ],
        ];
    }

    /**
     * A basic test example.
     *
     * @param array $input
     * @param array $databaseRecord
     * @return void
     * @dataProvider groupCategoryProvider
     */
    public function testGroupCategoriesInDatabase($input, $databaseRecord)
    {

        $category = GroupCategory::create($input);

        $this->assertInstanceOf(GroupCategory::class, $category);

        $testingRecord = $databaseRecord;
        $testingRecord['id'] = $category->id;

        $this->assertDatabaseHas('group_categories', $testingRecord);
    }

    /**
     * Data provider for testing groups in the database.
     *
     * @return array
     */
    public function groupProvider() {
        return [
            'MINIMUM' => [
                [
                    'name' => 'Minimum Test Group',
                ],
                [
                    'slug' => 'minimum-test-group',
                    'name' => 'Minimum Test Group',
                    'name_short' => null,
                    'description' => null,
                    'member_name' => null,
                    'is_required' => false
                ]
            ],
            'SIMPLE_BUT_MORE' => [
                [
                    'name' => 'Simple But More Test Group',
                    'name_short' => 'Simp. Test Group',
                    'description' => 'Een simpele test-groep met alle simpele parameters ingevuld.',
                    'member_name' => 'Simple Tester',
                    'is_required' => true,
                ],
                [
                    'name' => 'Simple But More Test Group',
                    'name_short' => 'Simp. Test Group',
                    'slug' => 'simp-test-group',
                    'description' => 'Een simpele test-groep met alle simpele parameters ingevuld.',
                    'member_name' => 'Simple Tester',
                    'is_required' => true,
                ]
            ],
            'NAMES_ONLY' => [
                [
                    'name' => 'Names Only Test Group',
                    'name_short' => 'Names Only Test',
                ],
                [
                    'slug' => 'names-only-test',
                    'name' => 'Names Only Test Group',
                    'name_short' => 'Names Only Test',
                    'description' => null,
                    'member_name' => null,
                    'is_required' => false,
                ]
            ]
        ];
    }

    /**
     * @param $input
     * @param $databaseRecord
     * @return void
     * @dataProvider groupProvider
     * @throws
     */
    public function testGroupsInDatabase($input, $databaseRecord) {

        $category = GroupCategory::firstOrCreate(['name' => 'Group Testing Test Category']);

        $this->assertInstanceOf(GroupCategory::class, $category);

        $group = $category->groups()->create($input);

        $testingRecord = $databaseRecord;
        $testingRecord['id'] = $group->id;
        $testingRecord['category_id'] = $category->id;

        $this->assertInstanceOf(Group::class, $group);
        $this->assertDatabaseHas('groups', $testingRecord);

        // Getting
        $collectedGroup = Group::findBySlug($group->slug);

        $this->assertInstanceOf(Group::class, $collectedGroup);
        $this->assertEquals($input['name'], $collectedGroup->name);

        if(array_get($input, 'name_short', null) !== null) {
            $this->assertEquals($input['name_short'], $collectedGroup->name_short);
        } else {
            $this->assertEquals($input['name'], $collectedGroup->name);
        }

        if(array_get($input, 'member_name', null) !== null) {
            $this->assertEquals($input['member_name'], $collectedGroup->member_name);
        } elseif(array_get($input, 'name_short', null) !== null) {
            $this->assertEquals($input['name_short'], $collectedGroup->member_name);
        } else {
            $this->assertEquals($input['name'], $collectedGroup->name);
        }

        $this->assertEquals($category->id, $collectedGroup->category->id);

        // Deletion
        $collectedGroup->delete();
        $this->assertTrue($collectedGroup->trashed());
        $this->assertDatabaseHas('groups', $testingRecord);

        // Force deletion
        $collectedGroup->forceDelete();
        $this->assertDatabaseMissing('groups', $testingRecord);

    }

    /**
     * Tests if the style of a category is passed on to the group in the right way.
     *
     * return @void
     */
    public function testGroupStyle() {

        $category = GroupCategory::create([
            'name' => 'Style Test Category',
            'style' => 'person-default',
        ]);

        $this->assertCount(0, $category->groups);

        $group = $category->groups()->make(['name' => 'Style Test Group']);

        $this->assertInstanceOf(Group::class, $group);
        $this->assertNull($group->id);

        $this->assertEquals('person-default', $category->style);
        $this->assertEquals('person-default', $group->style);

        $group->save();

        $this->assertNotNull($group->id);
        $this->assertDatabaseHas('groups', [
            'name' => 'Style Test Group',
            'id' => $group->id,
            'category_id' => $category->id,
        ]);

        $collectedCategory = GroupCategory::find($category->id);
        $this->assertCount(1, $collectedCategory->groups);

        $collectedGroup = $collectedCategory->groups[0];

        $this->assertEquals('person-default', $collectedCategory->style);
        $this->assertEquals('person-default', $collectedGroup->style);

    }

    /**
     * Tests if the group factory works properly. Especially if no category_id was given.
     *
     * This should also create a GroupCategory with "name_short" = 'Factory Groepen'. This should be the category
     * of all groups that had no category_id assigned to them.
     *
     * return @void
     */
    public function testGroupFactoryWithoutCategoryId() {
        $groupA = factory(Group::class)->create();
        $groupB = factory(Group::class)->create();

        $this->assertInstanceOf(Group::class, $groupA);
        $this->assertInstanceOf(Group::class, $groupB);
        $this->assertEquals($groupA->category_id, $groupB->category->id);

        $category = GroupCategory::where(['name_short' => 'Factory Groepen'])->first();

        $this->assertInstanceOf(GroupCategory::class, $category);
        $this->assertEquals($category->id, $groupA->category_id);
        $this->assertEquals($category->id, $groupB->category_id);
    }

    /**
     * Tests if the group factory works properly, Especially if a category_id was given.
     *
     * return @void
     */
    public function testGroupFactoryWithCategoryId() {
        $category = factory(GroupCategory::class)->create();

        $groupA = factory(Group::class)->create(['category_id' => $category->id]);
        $groupB = factory(Group::class)->create(['category_id' => $category->id]);

        $this->assertInstanceOf(GroupCategory::class, $category);
        $this->assertEquals($category->id, $groupA->category_id);
        $this->assertEquals($category->id, $groupB->category_id);

        $this->assertDatabaseMissing('group_categories', ['name_short' => 'Factory Groupen']);
    }
}
