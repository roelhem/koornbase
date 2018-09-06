<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 06-09-18
 * Time: 16:22
 */

namespace App\Http\GraphQL\Interfaces;


use App\Http\GraphQL\Fields\Stamps\DeletedAtField;
use App\Http\GraphQL\Fields\Stamps\DeletedByField;
use App\Http\GraphQL\Fields\Stamps\DestroyerField;
use Rebing\GraphQL\Support\InterfaceType;

class SoftDeleteModelInterface extends InterfaceType
{

    protected $attributes = [
        'name' => 'SoftDeleteModel',
        'description' => 'This Interface represents the Models that can be "Soft-deleted". When a Model is deleted this way, it is invisible for most of the users, but still exists in the database. This way, the data can be recovered if necessary.'
    ];

    public function fields()
    {
        return [
            'deleted_at' => DeletedAtField::class,
            'deleted_by' => DeletedByField::class,
            'destroyer'  => DestroyerField::class
        ];
    }

}