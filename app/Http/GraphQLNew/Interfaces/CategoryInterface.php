<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 14:13
 */

namespace App\Http\GraphQLNew\Interfaces;


use Roelhem\GraphQL\Facades\GraphQL;
use Roelhem\GraphQL\Types\InterfaceType;

class CategoryInterface extends InterfaceType
{

    public $name = 'Category';

    public $description = "The `Category` interface is used for dynamic models that can be used to categorize or
                           group a large collection of models.
                           \n\nFor the types of those models, some common structure is provided by this interface
                           to make it easier to create various UI-elements (like *select-menu's*, *table-filters*, 
                           etc.).";


    public function fields()
    {
        return [
            'slug' => [
                'description' => "This field gives a string that **uniquely identifies** this category/group and 
                                  is also *safe for URL's*. This makes it possible to create nicer and clearer
                                  URL's.
                                  \n\n - **Uniquely Identifies:** Means that for every model of this type, 
                                  there is only *one* model that has this value for it's 'slug'-field. Therefore,
                                  you only need to remember the 'slug'-value of a category/group to access the
                                  same model at the server.
                                  \n\n - **Safe for URL's:** Means that the value of this field only contains
                                  characters that are allowed to exist in URL's without changing the behaviour of
                                  this URL. Therefore, it's safe to use it anywhere within the URL.",
                'type' => GraphQL::type('String'),
                'importance' => 254,
            ],
            'name' => [
                'description' => 'This field contains the full name of the category/group.',
                'type' => GraphQL::type('String'),
                'importance' => 250,
            ],
            'shortName' => [
                'description' => "This field (may) contain a shorter version of the name of this category/group than
                                  the 'name'-field. It can be used at places in the UI where there is little space.",
                'type' => GraphQL::type('String'),
                'alias' => 'name_short',
                'importance' => 240,
            ],
            'description' => [
                'description' => "This field gives a description of the category/group. It may contain multi-line text.",
                'type' => GraphQL::type('String'),
                'importance' => 230,
            ]
        ];
    }
}