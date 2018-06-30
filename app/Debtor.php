<?php

namespace App;

use App\Contracts\OwnedByPerson;
use App\Traits\BelongsToPerson;
use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class Debtor extends Model implements OwnedByPerson
{

    use Userstamps;
    use BelongsToPerson;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MODEL CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    protected $table = 'debtors';

}
