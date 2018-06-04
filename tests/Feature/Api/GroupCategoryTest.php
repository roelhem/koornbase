<?php

namespace Tests\Feature\Api;

use App\GroupCategory;
use App\User;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GroupCategoryTest extends TestCase
{

    use RefreshDatabase, UsePassportAsAdmin;

    /**
     * Tests the basic usage of the index endpoint
     *
     * @return void
     */
    public function testIndex()
    {
        $this->asAdmin();

        $this->get('/api/group-categories')
            ->assertStatus(200)
            ->assertJsonCount(0, 'data');

        factory(GroupCategory::class, 3)->create();

        $this->get('/api/group-categories')
            ->assertStatus(200)
            ->assertJsonCount(3,'data');
    }

    /**
     * Test the basic usage of the show endpoint
     *
     * @return void
     */
    public function testShow()
    {
        $this->asAdmin();

        $this->get('/api/group-categories/1')->assertStatus(404);

        $category = factory(GroupCategory::class)->create();

        $response_by_id = $this->get("/api/group-categories/$category->id");
        $response_by_slug = $this->get("/api/group-categories/$category->slug");

        foreach ([$response_by_id, $response_by_slug] as $response) {
            $response->assertStatus(200)->assertJson([
                'data' => [
                    'id' => $category->id,
                    'slug' => $category->slug,
                    'name' => $category->name,
                    'name_short' => $category->name_short
                ]
            ]);
        }
    }

    /**
     * Tests the basic usage of the store endpoint
     */
    public function testStore()
    {
        $user = $this->asAdmin();

        $this->postJson('/api/group-categories', [])->assertStatus(422);

        $this->postJson('/api/group-categories', [
            'name' => 'Store Test Categorie 1'
        ])->assertStatus(201)->assertJson([
            'data' => [
                'name' => 'Store Test Categorie 1',
                'name_short' => 'Store Test Categorie 1',
                'slug' => 'store-test-categorie-1'
            ]
        ]);

        $this->assertDatabaseHas('group_categories', [
            'name' => 'Store Test Categorie 1',
            'name_short' => null,
            'description' => null,
            'style' => null,
            'slug' => 'store-test-categorie-1',
            'is_required' => false,
            'created_by' => $user->id,
            'updated_by' => $user->id
        ]);

        $this->postJson('/api/group-categories', [
            'name' => 'Store Test Categorie 2',
            'name_short' => 'Store Test 2',
            'description' => 'Store Test 2 Description',
            'style' => 'person-default'
        ])->assertStatus(201)->assertJson([
            'data' => [
                'name' => 'Store Test Categorie 2',
                'name_short' => 'Store Test 2',
                'slug' => 'store-test-2'
            ]
        ]);

        $this->assertDatabaseHas('group_categories', [
            'name' => 'Store Test Categorie 2',
            'name_short' => 'Store Test 2',
            'slug' => 'store-test-2',
            'description' => 'Store Test 2 Description',
            'style' => 'person-default',
            'created_by' => $user->id,
            'updated_by' => $user->id
        ]);

    }

    /**
     * Tests the basic usage of the update endpoint
     */
    public function testUpdate()
    {
        $user = $this->asAdmin();

        $this->putJson('/api/group-categories/1', [])->assertStatus(404);

        $category = factory(GroupCategory::class)->create(['name' => 'Update Test: startnaam']);

        $this->putJson("/api/group-categories/$category->id", [])->assertStatus(200);

        $this->assertDatabaseHas('group_categories', [
            'id' => $category->id,
            'updated_by' => $user->id,
        ]);

        $this->putJson("/api/group-categories/$category->id", [
            'name_short' => 'Update Test',
        ])->assertStatus(200);

        $this->assertDatabaseHas('group_categories', [
            'id' => $category->id,
            'name_short' => 'Update Test',
            'name' => 'Update Test: startnaam',
            'style' => null
        ]);

        $this->putJson("/api/group-categories/$category->id", [
            'name' => 'Update Test: updatenaam',
            'name_short' => null,
            'description' => 'Update Test: updated description',
            'style' => null
        ])->assertStatus(200);

        $this->assertDatabaseHas('group_categories', [
            'id' => $category->id,
            'name' => 'Update Test: updatenaam',
            'name_short' => null,
            'description' => 'Update Test: updated description',
            'style' => null
        ]);

        $this->putJson("/api/group-categories/$category->id", ["name" => null])->assertStatus(422);
    }

    /**
     * Testing the basic usage of the delete endpoint
     */
    public function testDelete() {
        $user = $this->asAdmin();

        $this->delete("/api/group-categories/1")->assertStatus(404);

        $deletableCategory = factory(GroupCategory::class)->create(['name' => 'Verwijderbare Group Category']);
        $requiredCategory = factory(GroupCategory::class)->create(['name' => 'Verplichte Group Category', 'is_required' => true]);

        $this->assertDatabaseHas('group_categories', [
            'id' => $deletableCategory->id,
            'is_required' => false
        ]);

        $this->assertDatabaseHas('group_categories', [
            'id' => $requiredCategory->id,
            'is_required' => true
        ]);

        $this->delete("/api/group-categories/$requiredCategory->id")->assertStatus(403);
        $this->delete("/api/group-categories/$requiredCategory->slug")->assertStatus(403);

        $this->delete("/api/group-categories/$deletableCategory->id")->assertStatus(200);

        $this->assertDatabaseHas('group_categories', [
            'id' => $deletableCategory->id,
            'deleted_by' => $user->id,
        ]);

        $this->assertDatabaseHas('group_categories', [
            'id' => $requiredCategory->id,
            'deleted_at' => null,
            'deleted_by' => null
        ]);
    }
}
