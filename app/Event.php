<?php

namespace App;

use App\Traits\HasStartEnd;
use App\Traits\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;

class Event extends Model
{

    use SoftDeletes;
    use Userstamps;
    use Sluggable;

    use HasStartEnd;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MODEL CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    protected $table = 'events';

    protected $dates = ['start','end','created_at', 'updated_at', 'deleted_at'];

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- RELATIONAL DEFINITIONS ----------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Gives the EventCategory of this Event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category() {
        return $this->belongsTo(EventCategory::class, 'category_id');
    }

    /**
     * Gives the Manager of this Event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function manager() {
        return $this->belongsTo(Person::class,'person_id');
    }

    /**
     * Gives the main Debtor of this Event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function debtor() {
        return $this->belongsTo(Debtor::class,'debtor_id');
    }

    /**
     * Gives the Venue of this Event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function venue() {
        return $this->belongsTo(Venue::class, 'venue_id');
    }

    /**
     * Gives the Jobs of this Event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function jobs() {
        return $this->hasMany(Job::class, 'event_id');
    }

    /**
     * Gives the Publications of this Event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function publications() {
        return $this->hasMany(Publication::class, 'event_id');
    }

    /**
     * Gives the Revenues of this Event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function revenues() {
        return $this->hasMany(Revenue::class, 'event_id');
    }

    /**
     * Gives the Expanses of this Event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function expanses() {
        return $this->hasMany(Expanse::class,'event_id');
    }

    /**
     * Gives all the Tags that were assigned to this Event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags() {
        return $this->belongsToMany(Tag::class, 'event_tag','event_id','tag_id');
    }

    /**
     * Gives the Rooms that were associated with this Event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function rooms() {
        return $this->belongsToMany(Room::class, 'event_room', 'event_id','room_id');
    }

    /**
     * Gives the Resources that were associated with this Event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function resources() {
        return $this->belongsToMany(Room::class, 'event_resources', 'event_id','resource_id');
    }

    /**
     * Gives the Persons that gave there status for this Event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function persons() {
        return $this->belongsToMany(Person::class, 'event_person_status', 'event_id','resource_id');
    }


}
