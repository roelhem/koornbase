<?php

namespace App;

use App\Traits\HasStartEnd;
use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class Job extends Model
{

    use Userstamps;

    use HasStartEnd;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MODEL CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    protected $table = 'jobs';

    protected $dates = ['start','end','created_at','updated_at'];

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- RELATIONAL DEFINITIONS ----------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Gives the JobCategory where this Job belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category() {
        return $this->belongsTo(JobCategory::class, 'category_id');
    }

    /**
     * Gives the Event where this Job belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event() {
        return $this->belongsTo(Event::class, 'event_id');
    }

    /**
     * Gives the Person that is registered to fulfill this Job. This relation is empty if a Person still has to be
     * found for the Job.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function person() {
        return $this->belongsTo(Person::class, 'person_id');
    }
}
