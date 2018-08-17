<?php

namespace App\Helpers;

use App\Enums\MembershipStatus;
use App\Person;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MembershipStatusChange
 *
 * @package App\Helpers
 * @property-read MembershipStatus $status
 * @property-read Carbon $date
 * @property-read integer $person_id
 * @property-read integer $membership_id
 * @property-read \App\Person $person
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Helpers\MembershipStatusChange past()
 * @mixin \Eloquent
 */
class MembershipStatusChange extends Model
{

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MODEL CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    protected $table = 'membership_status_changes';

    public $timestamps = false;
    public $incrementing = false;

    protected $dates = ['date'];

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- CUSTOM ACCESSORS ----------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    public function getStatusAttribute($value) {
        return MembershipStatus::get($value);
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- FORMATTING ----------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    public function asTimelineEvent() {
        return [
            'badge' => $this->status->getBackgroundClass(),
            'label' => $this->status->label,
            'date' => $this->date->format('d-m-Y')
        ];
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- SCOPES --------------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns only the status changes that happen in the past.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopePast($query)
    {
        return $query->whereDate('date', '<=', Carbon::today()->toDateString());
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- RELATIONAL DEFINITIONS ----------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * The Person this MembershipStatusChange belongs to
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function person() {
        return $this->belongsTo(Person::class, 'person_id');
    }


}
