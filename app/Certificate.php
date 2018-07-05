<?php

namespace App;

use App\Contracts\Finders\FinderCollection;
use App\Contracts\OwnedByPerson;
use App\Traits\BelongsToPerson;
use App\Traits\HasRemarks;
use App\Traits\HasStartEnd;
use Carbon\Carbon;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

/**
 * Class Certificate
 *
 * @package App
 *
 * @property integer $id
 * @property integer $category_id
 * @property boolean $passed
 * @property Carbon|null $examination_at
 * @property Carbon|null $valid_at
 * @property Carbon|null $expired_at
 *
 * @property-read CertificateCategory $category
 * @property-read boolean $is_valid
 */
class Certificate extends Model implements OwnedByPerson
{
    use Userstamps;
    use Filterable;

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
        if($at === null) {
            $at = Carbon::now();
        }

        if($at instanceof \DateTime) {
            $at = Carbon::instance($at);
        }

        if(!($at instanceof Carbon)) {
            $at = Carbon::parse($at);
        }

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
    
    public function getIsValidAttribute() {
        return $this->isValid();
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
        if(!($at instanceof Carbon)) {
            $at = Carbon::parse($at);
        }

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
        if(!($at instanceof Carbon)) {
            $at = Carbon::parse($at);
        }

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
