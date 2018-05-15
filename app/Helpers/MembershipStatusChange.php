<?php

namespace App\Helpers;

use App\Enums\MembershipStatus;
use App\Person;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MembershipStatusChange
 * @package App\Helpers
 *
 * @property integer $status
 * @property Carbon $date
 * @property integer $person_id
 * @property integer $membership_id
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
    // ----- FORMATTING ----------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    public function asTimelineEvent() {
        return [
            'badge' => MembershipStatus::getBackgroundClass($this->status),
            'label' => MembershipStatus::getLabel($this->status),
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
