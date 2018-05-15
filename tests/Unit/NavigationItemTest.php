<?php

namespace Tests\Unit;

use App\Services\Navigation\NavigationItem;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NavigationNavigationItemTest extends TestCase
{

    /**
     * Tests the baseAttributes
     *
     * @return void
     */
    public function testBaseAttributes()
    {
        $baseAttributes = NavigationItem::$baseAttributes;

        $item = new NavigationItem('test-item');

        foreach ($baseAttributes as $attribute) {
            $this->assertTrue($item->hasAttribute($attribute));
            $this->assertNull($item->getAttribute($attribute));
            $this->assertEquals('bar', $item->getAttribute($attribute, 'bar'));
            $this->assertFalse($item->hasStoredAttribute($attribute));
            $this->assertNull($item->getStoredAttribute($attribute));
            $this->assertEquals('bar', $item->getStoredAttribute($attribute, 'bar'));

            $this->assertTrue(isset($item[$attribute]));
            $this->assertNull($item[$attribute]);
        }
    }

    /**
     * Tests the defaultAttributes.
     *
     * @return void
     */
    public function testDefaultAttributes()
    {
        $defaultAttributes = NavigationItem::$defaultAttributes;

        $item = new NavigationItem('test-item');

        foreach ($defaultAttributes as $attribute => $defaultValue) {
            $this->assertTrue($item->hasAttribute($attribute));
            $this->assertEquals($defaultValue, $item->getAttribute($attribute));
            $this->assertEquals($defaultValue, $item->getAttribute($attribute, 'bar'));
            $this->assertFalse($item->hasStoredAttribute($attribute));
            $this->assertNull($item->getStoredAttribute($attribute));
            $this->assertEquals('bar', $item->getStoredAttribute($attribute, 'bar'));
        }
    }

    /**
     * Tests the derivedAttributes
     *
     * @return void
     */
    public function testDerivedAttributes()
    {
        $derivedAttributes = NavigationItem::$derivedAttributes;

        $item = new NavigationItem('test-item');

        foreach ($derivedAttributes as $attribute) {
            $this->assertTrue($item->hasAttribute($attribute));
            $this->assertFalse($item->hasStoredAttribute($attribute));
            $this->assertNull($item->getStoredAttribute($attribute));
            $this->assertEquals('bar', $item->getStoredAttribute($attribute, 'bar'));
        }
    }

    /**
     * Test the behavior of attributes that don't exist.
     *
     * @return void
     */
    public function testUndefinedAttributes()
    {
        $attribute = 'foo';

        $item = new NavigationItem('test-item');

        $this->assertFalse($item->hasAttribute($attribute));
        $this->assertNull($item->getAttribute($attribute));
        $this->assertEquals('bar', $item->getAttribute($attribute, 'bar'));
        $this->assertNull($item->getStoredAttribute($attribute));
        $this->assertEquals('bar', $item->getStoredAttribute($attribute, 'bar'));
    }

    /**
     * Tests if the provided $item has the expected attributes values.
     *
     * @param NavigationItem $item
     * @param array $expected
     *
     * @dataProvider itemProvider
     */
    public function testAttributeValues($item, $expected)
    {
        foreach ($expected as $attr => $value) {

            $this->assertTrue($item->hasAttribute($attr), "hasAttribute gives false for $attr");
            $this->assertEquals($value, $item->getAttribute($attr), "getAttribute gives wrong value for $attr");
            $this->assertTrue(isset($item[$attr]), "\$item[$attr] does not exist");
            $this->assertEquals($value, $item[$attr], "\$item[$attr] gives the wrong value");
            $this->assertTrue(isset($item->$attr), "\$item->$attr does not exist");
            $this->assertEquals($value, $item->$attr, "\$item->$attr gives the wrong value");
        }
    }

    public static function itemProvider()
    {
        $items = [];

        $d = NavigationItem::$defaultAttributes;

        // An empty item
        $items['EMPTY_NODE'] = [
            new NavigationItem('empty-item'),
            [
                'name' => null,
                'title' => null,
                'route' => null,
                'label' => null,
                'link' => null,
                'href' => $d['emptyHref'],
            ] + $d
        ];

        // A item with only a name.
        $items['ONLY_NAME_NODE'] = [
            new NavigationItem('name-only-item', [
                'name' => 'OnlyNameNavigationItem',
                'foo' => 'bar',
            ]),
            [
                'name' => 'OnlyNameNavigationItem',
                'title' => null,
                'route' => null,
                'label' => 'OnlyNameNavigationItem',
                'link' => null,
                'foo' => 'bar',
                'href' => $d['emptyHref'],
            ] + $d
        ];

        // A item with a name, title and link.
        $items['NAME_TITLE_AND_LINK_NODE'] = [
            new NavigationItem('name-title-link-item',[
                'name' => 'NameAndTitleNavigationItem',
                'title' => 'Title of NameAndTitleNavigationItem',
                'link' => 'path/to/something'
            ]),
            [
                'name' => 'NameAndTitleNavigationItem',
                'title' => 'Title of NameAndTitleNavigationItem',
                'route' => null,
                'label' => 'NameAndTitleNavigationItem',
                'link' => 'path/to/something',
                'href' => 'path/to/something',
            ] + $d
        ];

        // A item with a label.
        $items['LABEL_NODE'] = [
            new NavigationItem('label-item',[
                'title' => 'LabelNavigationItem',
                'label' => 'Label of LabelNavigationItem',
                'emptyHref' => '#',
                'icons' => [
                    'fe' => 'home',
                    'fa' => 'tick'
                ],
                'defaultIconStyle' => 'fa',
            ]),
            [
                'name' => null,
                'title' => 'LabelNavigationItem',
                'route' => null,
                'label' => 'Label of LabelNavigationItem',
                'link' => null,
                'href' => '#',
                'emptyHref' => '#',
                'icons' => [
                    'fe' => 'home',
                    'fa' => 'tick'
                ],
                'defaultIconStyle' => 'fa'
            ]
        ];

        // A item with a title only.
        $items['ONLY_TITLE_NODE'] = [
            new NavigationItem('only-title-item',[
                'title' => 'Title of TitleOnlyNavigationItem',
                'icons' => [
                    'fe' => 'fe',
                    'fa' => 'fa'
                ],
            ]),
            [
                'name' => null,
                'title' => 'Title of TitleOnlyNavigationItem',
                'route' => null,
                'label' => 'Title of TitleOnlyNavigationItem',
                'link' => null,
                'icons' => [
                    'fe' => 'fe',
                    'fa' => 'fa'
                ]
            ]
        ];

        return $items;
    }

    /**
     *
     *
     * @return void
     */
    public function testIconMethods() {

        $iconDef = [
            'fe' => 'home',
            'fa' => 'tick',
            'foo' => 'bar'
        ];

        $expected = [
            'fe' => 'fe fe-home',
            'fa' => 'fa fa-tick',
            'foo' => 'foo foo-bar',
            'undefined' => null
        ];

        $dStyle = NavigationItem::$defaultAttributes['defaultIconStyle'];

        // empty NavigationItem
        $item = new NavigationItem('empty-item');

        $this->assertNull($item->getIconClass());
        $this->assertNull($item->getIconClass('fe'));
        $this->assertNull($item->getIconClass('fa'));
        $this->assertNull($item->getIconClass('foo'));
        $this->assertNull($item->getIconClass('undefined'));

        // NavigationItem with the attribute icons set only.
        $item = new NavigationItem('icons-item',['icons' => $iconDef]);

        $this->assertEquals($iconDef, $item->icons);

        foreach ($expected as $style => $class) {
            $this->assertEquals($class, $item->getIconClass($style),"Testing the getIconClass('$style') output.");
        }

        $this->assertEquals($expected[$dStyle], $item->getIconClass());
        $item->defaultIconStyle = 'foo';
        $this->assertEquals($expected['foo'], $item->getIconClass());

    }
}
