<?php

namespace App;

use App\Contracts\OwnedByPerson;
use App\Services\Sorters\Traits\Sortable;
use App\Traits\HasRemarks;
use App\Traits\BelongsToPerson;
use App\Traits\PersonContactEntry\HasContactOptions;
use App\Traits\PersonContactEntry\OrderableWithIndex;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use App\Traits\Userstamps;
use Carbon\Carbon;


/**
 * Class PersonEmailAddress
 *
 * @package App
 * @property integer $id
 * @property string $label
 * @property string $email_address
 * @property Carbon|null $created_at
 * @property integer|null $created_by
 * @property Carbon|null $updated_at
 * @property integer|null $updated_by
 * @property \OptionsType $options
 * @property-read \App\Person $owner
 * @property-read \App\Person $person
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PersonEmailAddress filter($input = array(), $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PersonEmailAddress ownedBy($person_id)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PersonEmailAddress paginateFilter($perPage = null, $columns = array(), $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PersonEmailAddress simplePaginateFilter($perPage = null, $columns = array(), $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PersonEmailAddress sortBy($sortName, $direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PersonEmailAddress sortByList($sortList)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PersonEmailAddress whereBeginsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PersonEmailAddress whereEndsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PersonEmailAddress whereLike($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PersonEmailAddress whereOptions($options)
 * @mixin \Eloquent
 */
class PersonEmailAddress extends Model implements OwnedByPerson
{
    use Userstamps;
    use Filterable, Sortable, Searchable;

    use HasRemarks, BelongsToPerson;

    use HasContactOptions, OrderableWithIndex;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MODEL CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    protected $table = 'person_email_addresses';

    protected $fillable = ['label','email_address','options','remarks'];

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MAGIC METHODS -------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns the email_address as a string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->email_address ?? '(onbekend)';
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- SEARCHABLE CONFIG ---------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'label' => $this->label,
            'remarks' => $this->remarks,
            'emailAddress' => $this->email_address,
            'name' => $this->person->name_full,
        ];
    }

}
