<?php

namespace App;

use App\Services\Sorters\Traits\Sortable;
use App\Traits\HasDescription;
use App\Traits\HasShortName;
use App\Traits\Sluggable;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Wildside\Userstamps\Userstamps;

/**
 * Class CertificateCategory
 *
 * @package App
 * @property integer $id
 * @property integer|null $default_expire_years
 * @property boolean $is_required
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Certificate[] $certificates
 * @property-read string $name_short
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CertificateCategory filter($input = array(), $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CertificateCategory findSimilarSlugs($attribute, $config, $slug)
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\CertificateCategory onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CertificateCategory paginateFilter($perPage = null, $columns = array(), $pageName = 'page', $page = null)
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CertificateCategory simplePaginateFilter($perPage = null, $columns = array(), $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CertificateCategory sortBy($sortName, $direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CertificateCategory sortByList($sortList)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CertificateCategory whereBeginsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CertificateCategory whereEndsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CertificateCategory whereLike($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CertificateCategory whereSlug($slug)
 * @method static \Illuminate\Database\Query\Builder|\App\CertificateCategory withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\CertificateCategory withoutTrashed()
 * @mixin \Eloquent
 */
class CertificateCategory extends Model
{
    use SoftDeletes;
    use Userstamps;
    use Sluggable;
    use Filterable, Sortable, Searchable;

    use HasShortName, HasDescription;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MODEL CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    protected $table = 'certificate_categories';

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $fillable = ['name','name_short', 'slug','style','description','default_expire_years','is_required'];

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- RELATIONAL DEFINITIONS ----------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Gives all the Certificates that belong to this CertificateCategory
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function certificates() {
        return $this->hasMany(Certificate::class, 'category_id');
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
