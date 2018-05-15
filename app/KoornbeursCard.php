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
    public $incrementing = false;

    protected $dates = ['activated_at','deactivated_at','created_at', 'updated_at'];
    protected $fillable = ['id','activated_at','deactivated_at','version','remarks'];

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- RELATIONAL DEFINITIONS ----------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Gives the KoornbeursCardOwnerships of this KoornbeursCard.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ownerships() {
        return $this->hasMany(KoornbeursCardOwnership::class, 'card_id');
    }

    /**
     * Gives the most recent KoornbeursCardOwnership of this KoornbeursCard.
     *
     * @param Carbon|string|null $at
     * @return mixed
     */
    public function ownership($at = null) {
        return $this->hasOne(KoornbeursCardOwnership::class, 'card_id')->now($at)->orderByDesc('start');
    }

}
