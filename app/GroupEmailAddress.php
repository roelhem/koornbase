<?php

namespace App;

use App\Services\Sorters\Traits\Sortable;
use App\Traits\HasRemarks;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

/**
 * Class GroupEmailAddress
 * @package App
 *
 * @property-read integer $id
 * @property integer $group_id
 * @property string $email_address
 */
class GroupEmailAddress extends Model
{

    use Userstamps;
    use Filterable, Sortable;

    use HasRemarks;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MODEL CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    protected $table = 'group_email_addresses';

    protected $fillable = ['email_address','remarks'];

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- RELATIONAL DEFINITIONS ----------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Gives the group where this GroupEmailAddress belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group() {
        return $this->belongsTo(Group::class, 'group_id');
    }

}
