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

    protected $modelClass = Debtor::class;

}