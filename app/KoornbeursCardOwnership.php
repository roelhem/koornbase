<?php

namespace App;

use App\Traits\HasStartEnd;
use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class KoornbeursCardOwnership extends Model
{

    use Userstamps;

    use HasStartEnd;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MODEL CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    protected $table = 'koornbeurs_card_ownerships';

    protected $dates = ['start','end','created_at','updated_at'];

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- RELATIONAL DEFINITIONS ----------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Gives the Person where this KoornbeursCardOwnership belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function person() {
        return $this->belongsTo(Person::class, 'person_id');
    }

    /**
     * Gives the KoornbeursCard where this KoornbeursCardOwnership belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function card() {
        return $this->belongsTo(KoornbeursCard::class, 'card_id');
    }

}
