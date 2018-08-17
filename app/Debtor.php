<?php

namespace App;

use App\Contracts\OwnedByPerson;
use App\Services\Sorters\Traits\Sortable;
use App\Traits\BelongsToPerson;
use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

/**
 * App\Debtor
 *
 * @property-read \App\Person $owner
 * @property-read \App\Person $person
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Debtor ownedBy($person_id)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Debtor sortBy($sortName, $direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Debtor sortByList($sortList)
 * @mixin \Eloquent
 */
class Debtor extends Model implements OwnedByPerson
{

    use Userstamps;
    use BelongsToPerson;
    use Sortable;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MODEL CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    protected $table = 'debtors';

}
