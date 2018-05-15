<?php

namespace App;

use App\Traits\HasShortName;
use App\Traits\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;

class Room extends Model
{

    use SoftDeletes;
    use Userstamps;
    use HasShortName;
    use Sluggable;
    
    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MODEL CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    protected $table = 'rooms';

    protected $dates = ['created_at','updated_at','deleted_at'];

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- RELATIONAL DEFINITIONS ----------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //


    /**
     * Gives all the Events that are associated with this Room.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function events() {
        return $this->belongsToMany(Event::class, 'event_room',
            'room-id','event_id');
    }

    /**
     * Gives all the Resources that have some additional information about the availability in this Room.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function resources() {
        return $this->belongsToMany(Resource::class, 'room_resource',
            'room_id','resource_id');
    }

}
