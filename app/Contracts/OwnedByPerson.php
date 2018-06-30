<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 30-06-18
 * Time: 07:19
 */

namespace App\Contracts;


use App\Person;

interface OwnedByPerson
{

    /**
     * Returns the id of the person that ownes this object.
     *
     * Returns `null` if this object isn't owned by any Person.
     *
     * @return integer|null
     */
    public function getOwnerId();

    /**
     * Returns the instance of the person that ownes this person.
     *
     * Returns `null` if this object isn't owned by any Person.
     *
     * @return Person|null
     */
    public function getOwner();

}