<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 01:06
 */

namespace App\GraphQL\Queries;


use App\Debtor;

class DebtorQuery extends ModelViewQuery
{

    protected $attributes = [
        'name' => 'certificate'
    ];

    protected $typeName = 'Certificate';

    public function query($args, $selectFields)
    {
        return Debtor::query();
    }

}