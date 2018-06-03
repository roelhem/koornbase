<?php

namespace Tests\Feature\Finders;

use App\CertificateCategory;
use App\Group;
use App\GroupCategory;
use App\Services\Finders\CertificateCategoryFinder;
use App\Services\Finders\GroupCategoryFinder;
use App\Services\Finders\GroupFinder;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ModelByIdAndSlugFinderTest extends TestCase
{

    use RefreshDatabase;

    public function classNamesProvider() {
        return [
            "Group" => [Group::class, GroupFinder::class],
            "GroupCategory" => [GroupCategory::class, GroupCategoryFinder::class],
            "CertificateCategory" => [CertificateCategory::class, CertificateCategoryFinder::class]
        ];
    }

    /**
     * A basic test example.
     *
     * @dataProvider classNamesProvider
     * @param string $modelClass
     * @param string $finderClass
     * @return void
     */
    public function testAcceptAndFind($modelClass, $finderClass)
    {
        $finder = resolve($finderClass);

        $category = factory($modelClass)->create();

        $this->assertTrue($finder->accepts($category));
        $this->assertTrue($finder->accepts($category->id));
        $this->assertTrue($finder->accepts($category->slug));

        $this->assertFalse($finder->accepts(null));
        $this->assertFalse($finder->accepts([]));

        $foundByInstance = $finder->find($category);
        $foundById = $finder->find($category->id);
        $foundBySlug = $finder->find($category->slug);

        $this->assertInstanceOf($modelClass, $foundByInstance);
        $this->assertInstanceOf($modelClass, $foundById);
        $this->assertInstanceOf($modelClass, $foundBySlug);

        $this->assertEquals($category->id, $foundByInstance->id);
        $this->assertEquals($category->id, $foundById->id);
        $this->assertEquals($category->id, $foundBySlug->id);
    }
}
