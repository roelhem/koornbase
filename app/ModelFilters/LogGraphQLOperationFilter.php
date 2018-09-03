<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 03-09-18
 * Time: 08:25
 */

namespace App\ModelFilters;


use App\Enums\GraphQLOperationType;

class LogGraphQLOperationFilter extends ModelFilter
{

    public function schema($schema)
    {
        $this->where('schema','=', $schema);
    }

    public function type($type)
    {
        $type = GraphQLOperationType::get($type)->getValue();
        $this->where('type','=', $type);
    }

    public function operationName($name)
    {
        $this->where('operation_name','=',$name);
    }

    public function userId($user_id)
    {
        $this->where('user_id','=', $user_id);
    }

    public function clientId($client_id)
    {
        $this->where('client_id', '=', $client_id);
    }

    public function accessTokenId($access_token_id)
    {
        $this->where('access_token_id','=',$access_token_id);
    }

    public function before($date)
    {
        $this->where('before', '<=', \Parse::date($date, true));
    }

    public function after($date)
    {
        $this->where('after','>=', \Parse::date($date, true));
    }

}