<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 06-09-18
 * Time: 00:47
 */

namespace App\Http\GraphQL\Types;


use \Rebing\GraphQL\Support\PaginationType as BasePaginationType;

class PaginationType extends BasePaginationType
{
    public function __construct($typeName, $customName = null)
    {
        parent::__construct($typeName, $customName);
        $this->config['interfaces'] = [\GraphQL::type('Pagination')];
    }

    protected function getPaginationFields()
    {
        return \GraphQL::type('Pagination')->getFields();
    }
}