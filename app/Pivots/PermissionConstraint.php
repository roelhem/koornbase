<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 08-06-18
 * Time: 18:53
 */

namespace App\Pivots;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Class PermissionConstraint
 *
 *
 *
 * @package App\Pivots
 *
 * @property int $id
 * @property string $permission_id
 * @property string $constraint_id
 */
class PermissionConstraint extends Pivot
{

    protected $table = 'permission_constraint';

    protected $casts = [
        'params' => 'array'
    ];

}