<?php

namespace App;

use App\Traits\HasShortName;
use App\Traits\HasStringPrimaryKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;

class PublicationType extends Model
{

    use SoftDeletes;
    use Userstamps;
    use HasShortName;
    use HasStringPrimaryKey;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MODEL CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    protected $table = 'publication_types';
    protected $keyType = 'string';
    protected $incrementing = false;

    protected $dates = ['created_at','updated_at','deleted_at'];

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- RELATIONAL DEFINITIONS ----------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Gives all the Publications that belong to this PublicationType.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function publications() {
        return $this->hasMany(Publication::class, 'type_id');
    }

}
