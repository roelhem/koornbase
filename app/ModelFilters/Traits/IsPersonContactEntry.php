<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 05-07-18
 * Time: 10:16
 */

namespace App\ModelFilters\Traits;


trait IsPersonContactEntry
{

    use IsOwnedByPerson;

    /**
     * Filters the contract entries of a certain person.
     *
     * @param $id
     */
    public function personId($id)
    {
        $this->where('person_id',$id);
    }

    /**
     * Filters the contract entries with a certain index.
     *
     * @param $index
     */
    public function index($index)
    {
        $this->where('index', $index);
    }

    /**
     * Filters the contract entries with a certain label.
     *
     * @param $label
     */
    public function label($label)
    {
        $this->where('label','ILIKE',"%$label%");
    }

}