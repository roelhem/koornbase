<?php

namespace App;

use App\Interfaces\Rbac\RbacConstraint;
use App\Pivots\PermissionConstraint;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Constraint
 * @package App
 *
 * @property string $id
 * @property string|null $name
 * @property string|null $description
 *
 * @property-read Collection Permission
 */
class Constraint extends Model implements RbacConstraint
{

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MODEL CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    protected $table = 'constraints';
    protected $keyType = 'string';
    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = ['id','name','description'];

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- INTERFACE IMPLEMENTATION: RbacConstraint ----------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    public function getId()
    {
        return $this->id;
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- RELATIONAL DEFINITIONS ----------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    public function permissions() {
        return $this->belongsToMany(
            Permission::class,
            'permission_constraint',
            'constraint_id',
            'permission_id'
        )->using(PermissionConstraint::class);
    }
}
