<?php

namespace App;

use App\Traits\HasShortName;
use App\Traits\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;

class Venue extends Model
{

    use SoftDeletes;
    use Userstamps;
    use HasShortName;
    use Sluggable;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MODEL CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    protected $table = 'venues';

    protected $dates = ['created_at','updated_at','deleted_at'];

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- RELATIONAL DEFINITIONS ----------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Gives all the Events that are at this Venue.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function venues() {
        return $this->hasMany(Event::class, 'venue_id');
    }

}
