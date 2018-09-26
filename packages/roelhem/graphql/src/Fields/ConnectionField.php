<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 21-09-18
 * Time: 23:45
 */

namespace Roelhem\GraphQL\Fields;


use GraphQL\Type\Definition\FieldDefinition;
use Roelhem\GraphQL\Facades\GraphQL;

class ConnectionField extends FieldDefinition
{

    protected $typeName;

    public function __construct(array $config)
    {

        $this->typeName = array_get($config, 'typeName');

        parent::__construct(array_merge([
            'description' => $this->description,
            'args' => array_merge(
                $this->paginationArgs(),
                $this->orderByArgs()
            ),
        ], $config));
    }



    public function defaultDescription()
    {
        return "Gives a paginated list of the `{$this->typeName}`-typed items.";
    }




    public function paginationArgs()
    {
        return [
            'first' => [
                'type' => GraphQL::type('Int'),
                'description' => 'The maximum amount of items you want to display on one page.',
                'default' => 15,
            ],
            'after' => [
                'type' => GraphQL::type('Cursor'),
                'description' => 'The cursor to the position that should be the start of the page.',
            ],
            'offset' => [
                'type' => GraphQL::type('Int'),
                'description' => 'The number of items in the list that should be skipped.',
                'default' => 0
            ],
            'page' => [
                'type' => GraphQL::type('Int'),
                'description' => 'The number of the page that you want to display.',
                'default' => 1
            ],
        ];
    }

    public function orderByArgs()
    {
        return [
            'orderBy' => [
                'type' => GraphQL::type($this->typeName.'_orderByType'),
                'description' => 'Specifies how the items should be ordered in the resulting list'
            ]
        ];
    }
}