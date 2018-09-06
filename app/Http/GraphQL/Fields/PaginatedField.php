<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 06-09-18
 * Time: 07:10
 */

namespace App\Http\GraphQL\Fields;



use App\Person;
use GraphQL\Type\Definition\Type;
use Illuminate\Database\Eloquent\Builder;
use Rebing\GraphQL\Support\Field;

class PaginatedField extends Field
{

    /**
     * PaginatedField constructor.
     * @param string|Type $innerType
     * @param array $attributes
     */
    public function __construct($innerType, $attributes = [])
    {
        if(is_string($innerType)) {
            $innerType = \GraphQL::type($innerType);
        }
        $attributes['type'] = \GraphQL::paginate($innerType);

        parent::__construct($attributes);
    }

    /**
     * @return array
     */
    public function args()
    {
        return [
            'limit' => [
                'type' => Type::int(),
                'description' => 'The maximum amount of items that are shown on one page.',
                'default' => $this->get('defaultLimit',5),
            ],
            'page' => [
                'type' => Type::int(),
                'description' => 'The number of the page that you want to view.',
                'default' => 1,
            ]
        ];
    }

    /**
     * @param Person|Builder|\Laravel\Scout\Builder $query
     * @param array $args
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function resolve($root, $args)
    {
        $query = $root->groups();

        $per_page = array_get($args,'limit', 5);
        $page = array_get($args, 'page', 1);

        if($query instanceof \Laravel\Scout\Builder) {
            return $query->paginate($per_page, 'page', $page);
        } else {
            return $query->paginate($per_page, ['*'],'page', $page);
        }
    }

}