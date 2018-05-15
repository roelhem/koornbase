<?php

namespace App;

use App\Enums\BootstrapColors;
use App\Traits\HasOptions;
use App\Traits\HasShortName;
use App\Traits\HasStringPrimaryKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;

class EventCategory extends Model
{

    use SoftDeletes;
    use Userstamps;
    use HasShortName;
    use HasStringPrimaryKey;

    use HasOptions;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MODEL CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    protected $table = 'event_categories';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $fillable = ['id','name','name_short','description','is_public','style',
                        'is_required','visibility','remarks'];



    protected function defaultOptions(): array
    {
        return [
            'icons' => [],
            'iconColor' => BootstrapColors::Gray,
            'color' => null,
            'borderColor' => null,
            'backgroundColor' => null,
            'textColor' => null,
        ];
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- RELATIONAL DEFINITIONS ----------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Gives the Events that belong to this EventCategory.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function events() {
        return $this->hasMany(Event::class, 'category_id');
    }

}
