<?php

namespace App;

use App\Contracts\Finders\FinderCollection;
use App\Contracts\OwnedByPerson;
use App\Services\Sorters\Traits\Sortable;
use App\Traits\BelongsToPerson;
use App\Traits\HasRemarks;
use Carbon\Carbon;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Userstamps;

/**
 * Class Certificate
 *
 * @package App
 * @property integer $id
 * @property integer $category_id
 * @property boolean $passed
 * @property Carbon|null $examination_at
 * @property Carbon|null $valid_at
 * @property Carbon|null $expired_at
 * @property-read CertificateCategory $category
 * @property-read boolean $is_valid
 * @property-read \Carbon|null $valid_since
 * @property-read \Carbon|null $valid_till
 * @property-read \App\Person $owner
 * @property-read \App\Person $person
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Certificate filter($input = array(), $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Certificate invalid($at = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Certificate ownedBy($person_id)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Certificate paginateFilter($perPage = null, $columns = array(), $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Certificate simplePaginateFilter($perPage = null, $columns = array(), $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Certificate sortBy($sortName, $direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Certificate sortByList($sortList)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Certificate valid($at = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Certificate whereBeginsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Certificate whereEndsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Certificate whereLike($column, $value, $boolean = 'and')
 * @mixin \Eloquent
 */
class Certificate extends Model implements OwnedByPerson
{
    use Userstamps;
    use Filterable, Sortable;

    use HasRemarks, BelongsToPerson;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MODEL CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    public static function boot()
    {
        parent::boot();

        Certificate::creating(function(Certificate $model) {
            if($model->examination_at !== null && ($model->passed || $model->passed === null) && $model->valid_at === null) {
                $model->valid_at = $model->examination_at;
            }

            if($model->valid_at !== null && $model->expired_at === null) {
                $category = $model->category;
                if($category->default_expire_years !== null && $category->default_expire_years > 0) {
                    $model->expired_at = (clone $model->valid_at)->addYears($category->default_expire_years);
                }
            }
        });
    }

    protected $table = 'certificates';

    protected $dates = ['examination_at','valid_at','expired_at','created_at', 'updated_at'];

    protected $fillable = ['category_id','person_id','examination_at','passed','valid_at','expired_at','end','remarks'];

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- GETTER FUNCTIONS ----------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    public function isValid($at = null) {
        $at = \Parse::date($at, true);

        if($this->passed === false) {
            return false;
        }

        if($this->examination_at !== null && $this->examination_at > $at) {
            return false;
        }

        if($this->valid_at !== null && $this->valid_at > $at) {
            return false;
        }

        if ($this->expired_at !== null && $this->expired_at < $at) {
            return false;
        }

        return true;
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- CUSTOM ACCESSORS ----------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Attribute that stores if this certificate is valid at the current time.
     *
     * @return bool
     */
    public function getIsValidAttribute()
    {
        return $this->isValid();
    }

    /**
     * Attribute that gives the first date on which this certificate is valid. It will return null if it can't
     * find such a date.
     *
     * If a null value is returned, it can either mean that the certificate was never valid, or it means that
     * the certificate is valid, but there is no information on when the certificate started to be valid.
     *
     * @return Carbon|null
     */
    public function getValidSinceAttribute()
    {
        if($this->passed === false) {
            return null;
        }

        if($this->valid_at !== null) {
            return $this->valid_at;
        }

        if($this->examination_at !== null) {
            return $this->examination_at;
        }

        return null;
    }

    /**
     * Attribute that gives the last date on which this certificate is valid. This is an alias of the expired_at
     * attribute.
     *
     * @return Carbon|null
     */
    public function getValidTillAttribute()
    {
        return $this->expired_at;
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- SCOPES --------------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * A scope that only gives the certificates that are valid at the given time.
     *
     * @param Builder             $query
     * @param Carbon|string|null  $at
     * @return Builder
     */
    public function scopeValid($query, $at = null) {
        $at = \Parse::date($at, true);

        return $query->where('passed', true)
            ->where(function($subQuery) use ($at) {
                return $subQuery->whereNull('examination_at')->orWhereDate('examination_at', '<=', $at);
            })->where(function($subQuery) use ($at) {
                return $subQuery->whereNull('valid_at')->orWhereDate('valid_at', '<=', $at);
            })->where(function($subQuery) use ($at) {
                return $subQuery->whereNull('expired_at')->orWhereDate('expired_at', '>', $at);
            });
    }

    /**
     * A scope that only gives the certificates that are invalid at the given time.
     *
     * @param Builder $query
     * @param Carbon|string|null $at
     * @return Builder
     */
    public function scopeInvalid($query, $at = null) {
        $at = \Parse::date($at, true);

        return $query->where('passed', false)
            ->orWhere('examination_at','>',$at)
            ->orWhere('valid_at', '>', $at)
            ->orWhere('expired_at','<=', $at);
    }

    /**
     * A scope that only gives the certificates that have one of the specified categories
     *
     * @param Builder $query
     * @param mixed $categories
     * @return Builder
     */
    public function scopeCategory($query, $categories) {
        $categories = collect($categories);
        $category_ids = $categories->map(function($category) {
            if(is_integer($category)) {
                return $category;
            } else {
                return resolve(FinderCollection::class)->find($category, 'certificate_category')->id;
            }
        });
        return $query->whereIn('category_id',$category_ids);
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- RELATIONAL DEFINITIONS ----------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Gives the CertificateCategory of this Certificate
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category() {
        return $this->belongsTo(CertificateCategory::class, 'category_id');
    }
}
