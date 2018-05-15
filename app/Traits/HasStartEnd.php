<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 09-05-18
 * Time: 22:45
 */

namespace App\Traits;
use App\Enums\Chronology;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;


/**
 * Trait HasStartEnd
 *
 * This trait should be added to all the Models that have a start and end that determine when the model is active.
 *
 * @package App\Traits
 */
trait HasStartEnd
{

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- CHECKING CHRONOLOGICAL ORDER ----------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns if the current Model is in the future.
     *
     * @param Carbon|string|null $at
     * @return bool
     */
    public function isFuture($at = null) {
        $at = $this->parseAt($at);
        if($this->start !== null && $this->start > $at) {
            return true;
        }

        return false;
    }

    /**
     * Return if the current Model is in the past.
     *
     * @param Carbon|string|null $at
     * @return bool
     */
    public function isPast($at = null) {
        $at = $this->parseAt($at);
        if($this->end !== null && $this->end < $at) {
            return true;
        }

        return false;
    }

    /**
     * Returns if the current Model is now.
     *
     * @param Carbon|string|null $at
     * @return bool
     */
    public function isNow($at = null) {
        return !($this->isFuture($at) || $this->isPast($at));
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- CUSTOM ACCESSORS ----------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns the value of the App\Enum\Chronology enum that belongs to the start/end time of this Model.
     *
     * @return integer
     */
    public function getChronologyAttribute() {
        if($this->isPast()) {
            return Chronology::Past;
        } elseif($this->isFuture()) {
            return Chronology::Future;
        } else {
            return Chronology::Now;
        }
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- SCOPES --------------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Scope that only gives the Models with a start/end in the future.
     *
     * @param Builder $query
     * @param Carbon|string|null $at
     * @return Builder
     */
    public function scopeFuture($query, $at = null) {
        $at = $this->parseAt($at);
        return $query->whereNotNull('start')->where('start', '>', $at->toDateTimeString());
    }

    /**
     * Scope that only gives the Models with a start/end in the past.
     *
     * @param Builder $query
     * @param Carbon|string|null $at
     * @return Builder
     */
    public function scopePast($query, $at = null) {
        $at = $this->parseAt($at);
        return $query->whereNotNull('end')->where('end', '<', $at->toDateTimeString());
    }

    /**
     * Scope that only gives the Models with a start/end surrounding the current time.
     *
     * @param Builder $query
     * @param Carbon|string|null $at
     * @return Builder
     */
    public function scopeNow($query, $at = null) {
        $at = $this->parseAt($at);
        return $query->where(function ($query) use ($at) {
            $query->orWhereNull('start')->orWhere('start', '<=', $at->toDateTimeString());
        })->where(function($query) use ($at) {
            $query->orWhereNull('end')->orWhere('end', '>=', $at->toDateTimeString());
        });
    }

    /**
     * @param $query
     * @param int $chronology
     * @param null $at
     * @return Builder
     */
    public function scopeChronology($query, $chronology = 0, $at = null) {
        switch ($chronology) {
            case Chronology::Past:
                return $this->scopePast($query, $at);
            case Chronology::Now:
                return $this->scopeNow($query, $at);
            case Chronology::Future:
                return $this->scopeFuture($query, $at);
        }
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- HELPER FUNCTIONS ----------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns the right Carbon value for the given $at attribute.
     *
     * @param $at
     * @return Carbon
     */
    private function parseAt($at)
    {
        if($at === null) {
            return Carbon::now();
        } elseif($at instanceof Carbon) {
            return $at;
        } else {
            return Carbon::parse($at);
        }
    }

}