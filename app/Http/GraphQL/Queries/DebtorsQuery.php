<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 14-07-18
 * Time: 23:13
 */

namespace App\Http\GraphQL\Queries;


use App\Debtor;

class DebtorsQuery extends ModelListQuery
{

    protected $typeName = 'Debtor';


}