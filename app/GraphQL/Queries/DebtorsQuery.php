<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 14-07-18
 * Time: 23:13
 */

namespace App\GraphQL\Queries;


use App\Debtor;

class DebtorsQuery extends ModelListQuery
{

    protected $attributes = [
        'name' => 'debtors'
    ];

    protected $typeName = 'Debtor';

    /** @inheritdoc */
    public function query($args, $selectFields)
    {
        return Debtor::query();
    }

}