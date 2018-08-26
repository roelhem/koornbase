<?php

namespace App;


use App\Services\Sorters\Traits\Sortable;
use App\Traits\HasDescription;
use App\Traits\HasOptions;
use App\Traits\HasShortName;
use App\Traits\Sluggable;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Laravel\Scout\Searchable;
use Roelhem\RbacGraph\Contracts\Models\AuthorizableGroup;
use Roelhem\RbacGraph\Contracts\Models\RbacDatabaseAssignable;
use Roelhem\RbacGraph\Database\Traits\HasMorphedRbacAssignments;
use App\Traits\Userstamps;

/**
 * Class GroupCategory
 *
 * @package App
 * @property integer $id
 * @property-read string $slug
 * @property boolean $is_required
 * @property string $style
 * @property-read Collection|Group[] $groups
 * @property-read \Illuminate\Database\Eloquent\Collection|\Roelhem\RbacGraph\Database\Node[] $assignedNodes
 * @property-read \Illuminate\Database\Eloquent\Collection|\Roelhem\RbacGraph\Database\Assignment[] $assignments
 * @property-read string $name_short
 * @property \OptionsType $options
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GroupCategory filter($input = array(), $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GroupCategory findSimilarSlugs($attribute, $config, $slug)
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\GroupCategory onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GroupCategory paginateFilter($perPage = null, $columns = array(), $pageName = 'page', $page = null)
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GroupCategory simplePaginateFilter($perPage = null, $columns = array(), $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GroupCategory sortBy($sortName, $direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GroupCategory sortByList($sortList)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GroupCategory whereBeginsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GroupCategory whereEndsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GroupCategory whereLike($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GroupCategory whereOptions($options)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GroupCategory whereSlug($slug)
 * @method static \Illuminate\Database\Query\Builder|\App\GroupCategory withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\GroupCategory withoutTrashed()
 * @mixin \Eloquent
 */
class GroupCategory extends Model implements RbacDatabaseAssignable, AuthorizableGroup
{

    use SoftDeletes;
    use Userstamps;
    use Sluggable;
    use Filterable, Sortable, Searchable;

    use HasShortName, HasDescription;
    use HasOptions;

    use HasMorphedRbacAssignments;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MODEL CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    protected $table = 'group_categories';

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $fillable = ['name','name_short', 'slug','style','description','is_required', 'options'];

    protected function defaultOptions(): array
    {
        return [
            'showOnPersonsPage' => true,
        ];
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- RELATIONAL DEFINITIONS ----------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //


    /**
     * Gives all the Groups that belong to this GroupCategory.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function groups() {
        return $this->hasMany(Group::class, 'category_id');
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- IMPLEMENTATION: AuthorizableGroup ------------------------------------------------------------------ //
    // ---------------------------------------------------------------------------------------------------------- //

    public function getAuthorizables()
    {
        return $this->groups;
    }

    public function getAuthorizableGroups()
    {
        return collect([]);
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
            'description'
        ]);
    }

}
