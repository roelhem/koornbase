<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 30-06-18
 * Time: 07:19
 */

namespace App\Contracts;


use App\Person;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Interface OwnedByPerson
 * @package App\Contracts
 *
 * @property-read Person $owner
 */
interface OwnedByPerson
{

    /**
     * Defines a relation between this model and its owner.
     *
     * @return BelongsTo
     */
    public function owner();

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

    /**
     * Scope that filters only the objects that are owned by the Person with the provided id.
     *
     * @param Builder $query
     * @param integer $person_id
     * @return Builder
     */
    public function scopeOwnedBy($query, $person_id);

}