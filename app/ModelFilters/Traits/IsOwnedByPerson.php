<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 05-07-18
 * Time: 10:27
 */

namespace App\ModelFilters\Traits;


trait IsOwnedByPerson
{

    /**
     * Filters the models that are owned by the provided person.
     *
     * @param $person_id
     */
    public function ownedBy($person_id)
    {
        $this->query->ownedBy($person_id);
    }

}