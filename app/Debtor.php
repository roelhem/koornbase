<?php

namespace App;

use App\Contracts\OwnedByPerson;
use App\Services\Sorters\Traits\Sortable;
use App\Traits\BelongsToPerson;
use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

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
