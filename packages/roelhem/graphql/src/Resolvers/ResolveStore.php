<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 21:06
 */

namespace Roelhem\GraphQL\Resolvers;


use GraphQL\Type\Definition\FieldDefinition;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Fluent;

/**
 * Class ResolveStore
 * @package Roelhem\GraphQL\Resolvers
 * @mixin ResolveInfo
 * @property FieldDefinition $field
 */
class ResolveStore extends Fluent
{

    public $info;

    /**
     * ResolveStore constructor.
     * @param ResolveInfo $info
     */
    public function __construct(ResolveInfo $info)
    {
        // initiate the parent
        parent::__construct(get_object_vars($info));

        // store the info-object
        $this->info = $info;

        // Add some extra attributes that are very useful.
        try {
            $this->field = $this->parentType->getField($this->info->fieldName);
        } catch (\Exception $exception) {};

    }
}