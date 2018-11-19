<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 19/11/2018
 * Time: 20:12
 */

namespace Roelhem\GraphQL\Types;


abstract class PivotType extends ObjectType
{

    /**
     * The class name of the class that represents the pivot of this PivotType.
     *
     * @var string
     */
    protected $pivotClass;

    /**
     * Returns the name of the class that represents this pivot.
     *
     * @return string
     */
    public function getPivotClass()
    {
        return $this->pivotClass;
    }


}