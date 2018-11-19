<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 14:01
 */

namespace App\Http\GraphQL\Types\Models;


use App\CertificateCategory;
use Roelhem\GraphQL\Facades\GraphQL;
use Roelhem\GraphQL\Types\ModelType;

class CertificateCategoryType extends ModelType
{

    public $modelClass = CertificateCategory::class;

    public $name = 'CertificateCategory';

    public $description = 'The `CertificateCategory`-type models a category of certificate. This object contains all 
                           the information about what it actually means to have a certain `Certificate`.';

    public function fields()
    {
        return [
            'defaultExpireYears' => [
                'description' => "The default amount of years that a `Certificate` of this category is valid. If the
                                  value is `null`, it means that certificates of this category never expire by
                                  default.",
                'type' => GraphQL::type('Int'),
                'importance' => -1,
            ],

        ];
    }

    public function interfaces()
    {
        return array_merge([GraphQL::type('Category')], parent::interfaces());
    }

    public function connections()
    {
        return [
            'certificates' => [
                'to' => 'Certificate',
                'description' => 'List of all the certificates that belong to this category',
            ]
        ];
    }

    protected function orderables()
    {
        return array_merge(parent::orderables(), [
            'name' => [
                'description' => 'Orders the categories by the name.',
                'query' => ['name','name_short','id'],
                'cursorPattern' => ['name' => 'a*','name_short' => 'a*','id' => 'n'],
            ],
            'shortName' => [
                'description' => 'Orders the categories by the short name.',
                'query' => ['name_short','name','id'],
                'cursorPattern' => ['name_short' => 'a*','name' => 'a*','id' => 'n'],
            ],
        ]);
    }

}