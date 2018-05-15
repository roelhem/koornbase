<?php

namespace App;

use App\Traits\HasShortName;
use App\Traits\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;

class Resource extends Model
{

    use SoftDeletes;
    use Userstamps;
    use HasShortName;
    use Sluggable;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MODEL CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    protected $table = 'resources';

    protected $dates = ['created_at','updated_at','deleted_at'];

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- RELATIONAL DEFINITIONS ----------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Gives the ResourceCategory where this Resource belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category() {
        return $this->belongsTo(ResourceCategory::class, 'category_id');
    }

    /**
     * Gives the parent Resource of this Resource.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent() {
        return $this->belongsTo(Resource::class, 'parent_id');
    }

    /**
     * Gives all the child Resources of this Resource.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children() {
        return $this->hasMany(Resource::class, 'parent_id');
    }

    /**
     * Gives all the Rooms where some additional information about the availability of this Resource is given.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function rooms() {
        return $this->belongsToMany(Room::class,'room_resource','resource_id','room_id');
    }

    /**
     * Gives all the Events that where somehow associated with this Resource.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function events() {
        return $this->belongsToMany(Event::class, 'event_resource','resource_id','event_id');
    }
}
