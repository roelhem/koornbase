<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 15:19
 */

namespace App\Http\GraphQL\Types\Models;


use App\GroupCategory;
use Roelhem\GraphQL\Facades\GraphQL;
use Roelhem\GraphQL\Types\ModelType;

class GroupCategoryType extends ModelType
{

    public $modelClass = GroupCategory::class;

    public $name = 'GroupCategory';

    public $description = 'The `GroupCategory`-typed models makes it easier to distinguish the 
                          `Group`s form each other.';

    public function fields()
    {
        return [
            'style' => [
                'description' => "The name of the (visual) style of a `Group` in this `GroupCategory`.",
                'type' => GraphQL::type('String'),
                'importance' => 200,
            ],
        ];
    }

    public function interfaces()
    {
        return array_merge([GraphQL::type('Category')], parent::interfaces());
    }

    public function connections()
    {
        return [
            'groups' => [
                'to' => 'Group',
                'description' => 'A list of all the groups that belong to this category.',
            ]
        ];
    }

    protected function orderables()
    {
        return array_merge(parent::orderables(), [
            'name' => [
                'description' => 'Orders the categories by the name.',
                'query' => ['name','name_short','id'],
                'cursorPattern' => ['name' => 'a*','name_short' => 'a*','id' => 'n'],
            ],
            'shortName' => [
                'description' => 'Orders the categories by the short name.',
                'query' => ['name_short','name','id'],
                'cursorPattern' => ['name_short' => 'a*','name' => 'a*','id' => 'n'],
            ],
        ]);
    }

    public function filters()
    {
        return [
            'style' => [
                'type' => GraphQL::type('String'),
                'description' => 'Filters all the GroupCategories with the provided style.'
            ]
        ];
    }

}