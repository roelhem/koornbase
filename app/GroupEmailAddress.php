<?php

namespace App;

use App\Services\Sorters\Traits\Sortable;
use App\Traits\HasRemarks;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Userstamps;

/**
 * Class GroupEmailAddress
 *
 * @package App
 * @property-read integer $id
 * @property integer $group_id
 * @property string $email_address
 * @property-read \App\Group $group
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GroupEmailAddress filter($input = array(), $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GroupEmailAddress paginateFilter($perPage = null, $columns = array(), $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GroupEmailAddress simplePaginateFilter($perPage = null, $columns = array(), $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GroupEmailAddress sortBy($sortName, $direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GroupEmailAddress sortByList($sortList)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GroupEmailAddress whereBeginsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GroupEmailAddress whereEndsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GroupEmailAddress whereLike($column, $value, $boolean = 'and')
 * @mixin \Eloquent
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
