<?php

namespace App;

use App\Contracts\Finders\FinderCollection;
use App\Services\Sorters\Traits\Sortable;
use App\Traits\HasDescription;
use App\Traits\HasShortName;
use App\Traits\Sluggable;
use Carbon\Carbon;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Laravel\Scout\Searchable;
use Roelhem\RbacGraph\Contracts\Models\AuthorizableGroup;
use Roelhem\RbacGraph\Contracts\Models\RbacDatabaseAssignable;
use Roelhem\RbacGraph\Database\Traits\HasMorphedRbacAssignments;
use Wildside\Userstamps\Userstamps;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Group
 *
 * @package App
 * @property integer $id
 * @property string $category_id
 * @property string $slug
 * @property string $member_name
 * @property boolean $is_required
 * @property-read GroupCategory $category
 * @property-read Collection|Person[] $persons
 * @property-read Carbon $created_at
 * @property-read integer|null $created_by
 * @property-read Carbon $updated_at
 * @property-read integer|null $updated_by
 * @property-read Carbon|null $deleted_at
 * @property-read integer|null $deleted_by
 * @property-read string $style
 * @property-read \Illuminate\Database\Eloquent\Collection|\Roelhem\RbacGraph\Database\Node[] $assignedNodes
 * @property-read \Illuminate\Database\Eloquent\Collection|\Roelhem\RbacGraph\Database\Assignment[] $assignments
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\GroupEmailAddress[] $emailAddresses
 * @property-read string $name_short
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Group category($categories)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Group filter($input = array(), $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Group findSimilarSlugs($attribute, $config, $slug)
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Group onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Group paginateFilter($perPage = null, $columns = array(), $pageName = 'page', $page = null)
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Group simplePaginateFilter($perPage = null, $columns = array(), $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Group sortBy($sortName, $direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Group sortByList($sortList)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Group whereBeginsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Group whereEndsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Group whereLike($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Group whereSlug($slug)
 * @method static \Illuminate\Database\Query\Builder|\App\Group withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Group withoutTrashed()
 * @mixin \Eloquent
 */
class Group extends Model implements RbacDatabaseAssignable, AuthorizableGroup
{

    use Filterable;
    use SoftDeletes;
    use Userstamps;
    use Sluggable, Sortable, Searchable;

    use HasShortName, HasDescription, HasMorphedRbacAssignments;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MODEL CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    protected $table = 'groups';

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $fillable = ['category_id','slug','name','name_short','description','member_name','is_required'];

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- ACCESSOR METHODS ----------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    public function getMemberNameAttribute($value) {
        if(empty($value)) {
            return $this->name_short;
        } else {
            return $value;
        }
    }

    public function getStyleAttribute() {
        return $this->category->style;
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- RELATIONAL DEFINITIONS ----------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Gives the GroupCategory where this Group belongs to.
     *
     * @return BelongsTo
     */
    public function category() {
        return $this->belongsTo(GroupCategory::class, 'category_id');
    }

    /**
     * Gives all the Persons that belong to this Group.
     *
     * @return BelongsToMany
     */
    public function persons() {
        return $this->belongsToMany(Person::class, 'person_group','group_id','person_id');
    }

    /**
     * Gives the GroupEmailAdresses that belong to this Group.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function emailAddresses() {
        return $this->hasMany(GroupEmailAddress::class, 'group_id');
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- SCOPES --------------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Scope that only passes the groups that are in one of the specified categories.
     *
     * @param Builder $query
     * @param array|string|integer $categories
     * @return Builder
     */
    public function scopeCategory($query, $categories) {
        $categories = collect($categories);
        $category_ids = $categories->map(function($category) {
            if(is_integer($category)) {
                return $category;
            } else {
                return resolve(FinderCollection::class)->find($category, 'group_category')->id;
            }
        });
        return $query->whereIn('category_id',$category_ids);
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- IMPLEMENTATION: AuthorizableGroup ------------------------------------------------------------------ //
    // ---------------------------------------------------------------------------------------------------------- //


    public function getAuthorizables()
    {
        return $this->persons;
    }

    public function getAuthorizableGroups()
    {
        return collect([$this->category]);
    }

    public function getDynamicRoles()
    {
        return collect([]);
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- SEARCHABLE CONFIGURATION --------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    public function toSearchableArray()
    {
        return $this->only([
            'id',
            'slug',
            'name',
            'name_short',
            'description',
            'member_name'
        ]);
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- SEND NOTIFICATIONS TO ALL CURRENT MEMBERS ---------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Sends the provided notification to all the persons in this group.
     *
     * @param $instance
     */
    public function notify($instance)
    {
        \Notification::send($this->persons, $instance);
    }

    /**
     * Immediately sends the provided notification to all the persons in this group.
     *
     * @param $instance
     */
    public function notifyNow($instance)
    {
        \Notification::sendNow($this->persons, $instance);
    }


}
