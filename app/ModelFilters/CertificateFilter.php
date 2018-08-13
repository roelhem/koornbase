<?php

namespace App\ModelFilters;

use App\ModelFilters\Traits\IsOwnedByPerson;

class CertificateFilter extends ModelFilter
{

    use IsOwnedByPerson;

    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [
        'person' => ['membership_status']
    ];

    /**
     * Filter on the valid state of a certificate.
     *
     * @param mixed $state
     */
    public function isValid($state)
    {
        if($state) {
            $this->query->valid();
        } else {
            $this->query->invalid();
        }
    }

    /**
     * Filters the certificates that are valid at a certain date.
     *
     * @param mixed $date
     */
    public function validAt($date)
    {
        $this->query->valid($date);
    }

    /**
     * Filters the certificates that are invalid at a certain date.
     *
     * @param mixed $date
     */
    public function invalidAt($date)
    {
        $this->query->invalid($date);
    }

    /**
     * Filters the certificates that belong to one of the provided categories.
     *
     * @param $id
     */
    public function categoryId($id)
    {
        $this->where('category_id',$id);
    }

    /**
     * Filters the certificates that belong to the provided person.
     *
     * @param $id
     */
    public function personId($id)
    {
        $this->where('person_id',$id);
    }

}
