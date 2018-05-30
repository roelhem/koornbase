<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class KoornbeursCard extends Model
{

    use Userstamps;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MODEL CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    protected $table = 'koornbeurs_cards';

    protected $dates = ['activated_at','deactivated_at','created_at', 'updated_at'];
    protected $fillable = ['id','ref','version','remarks'];

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- RELATIONAL DEFINITIONS ----------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Gives the Owner of this KoornbeursCard.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner() {
        return $this->belongsTo(Person::class, 'owner_id');
    }

}
