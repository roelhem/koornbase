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
 *
 * @property integer $id
 * @property integer|null $default_expire_years
 * @property boolean $is_required
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
