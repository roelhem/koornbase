<?php

namespace App;

use App\Traits\HasStartEnd;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

/**
 * Class GroupMembership
 *
 * @package App
 *
 * @property integer $id
 * @property integer $person_id
 * @property integer $group_id
 * @property Carbon|null $start
 * @property Carbon|null $end
 *
 * @property string|null $remarks
 *
 * @property Carbon|null $created_at
 * @property integer|null $created_by
 * @property Carbon|null $updated_at
 * @property integer|null $updated_by
 */
class GroupMembership extends Model
{

    use Userstamps;

    use HasStartEnd;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MODEL CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    protected $table = 'group_memberships';

    protected $dates = ['start', 'end', 'created_at', 'updated_at'];

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- RELATIONAL DEFINITIONS ----------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Gives the Person where this GroupMembership belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function person() {
        return $this->belongsTo(Person::class, 'person_id');
    }

    /**
     * Gives the Group where this GroupMembership belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group() {
        return $this->belongsTo(Group::class, 'group_id');
    }
}
