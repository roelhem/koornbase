<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 11:01
 */

namespace App\GraphQL\Types;

use App\GraphQL\Fields\Stamps\CreatedAtField;
use App\GraphQL\Fields\Stamps\CreatedByField;
use App\GraphQL\Fields\Stamps\CreatorField;
use App\GraphQL\Fields\Stamps\EditorField;
use App\GraphQL\Fields\Stamps\UpdatedAtField;
use App\GraphQL\Fields\Stamps\UpdatedByField;
use App\PersonPhoneNumber;
use GraphQL;
use GraphQL\Type\Definition\Type;
use libphonenumber\geocoding\PhoneNumberOfflineGeocoder;
use Rebing\GraphQL\Support\Type as GraphQLType;

class PersonPhoneNumberType extends GraphQLType
{

    /**
     * @var PhoneNumberOfflineGeocoder
     */
    protected $geocoder;


    /**
     * PersonPhoneNumberType constructor.
     * @param array $attributes
     * @param PhoneNumberOfflineGeocoder $geocoder
     */
    public function __construct($attributes = [], PhoneNumberOfflineGeocoder $geocoder)
    {
        $this->geocoder = $geocoder;

        parent::__construct($attributes);
    }

    protected $attributes = [
        'name' => 'PersonPhoneNumber',
        'model' => PersonPhoneNumber::class
    ];

    /** @inheritdoc */
    public function interfaces()
    {
        return [
            GraphQL::type('Model'),
            GraphQL::type('OwnedByPerson'),
            GraphQL::type('PersonContactEntry'),
            GraphQL::type('BelongsToCountry')
        ];
    }

    /** @inheritdoc */
    public function fields()
    {

        $ownedByPersonInterface = GraphQL::type('OwnedByPerson');
        $personContactEntryInterface = GraphQL::type('PersonContactEntry');
        $belongsToCountryInterface = GraphQL::type('BelongsToCountry');

        return [
            GraphQL::type('Model')->getField('id'),
            $ownedByPersonInterface->getField('owner_id'),
            $ownedByPersonInterface->getField('owner'),

            $personContactEntryInterface->getField('person_id'),
            $personContactEntryInterface->getField('person'),
            $personContactEntryInterface->getField('index'),
            $personContactEntryInterface->getField('label'),

            'phone_number' => [
                'type' => Type::string(),
                'args' => [
                    'format' => [
                        'type' => GraphQL::type('PhoneNumberFormat'),
                        'description' => 'The format of the phone number.'
                    ],
                    'from' => [
                        'type' => Type::string(),
                        'description' => 'Formats the phone number to call from this country.'
                    ]
                ],
                'resolve' => function(PersonPhoneNumber $root, $args) {
                    $format = array_get($args,'format', 'FOR_MOBILE');
                    if(is_integer($format)) {
                        return $root->format($format);
                    } else {
                        $countryCode = array_get($args, 'from','NL');
                        switch ($format) {
                            case 'FOR_FIXED': return $root->formatFor($countryCode);
                            case 'FOR_MOBILE': return $root->formatMobile($countryCode,true);
                            case 'FOR_MOBILE_COMPACT': return $root->formatMobile($countryCode, false);
                        }
                    }
                    return null;
                }
            ],

            $belongsToCountryInterface->getField('country_code'),
            $belongsToCountryInterface->getField('country'),

            'location' => [
                'type' => Type::string(),
                'resolve' => function(PersonPhoneNumber $root) {
                    return $this->geocoder->getDescriptionForNumber($root->phone_number, 'nl_NL');
                }
            ],

            'number_type' => [
                'type' => GraphQL::type('PhoneNumberType')
            ],

            'created_at' => CreatedAtField::class,
            'created_by' => CreatedByField::class,
            'creator'    => CreatorField::class,
            'updated_at' => UpdatedAtField::class,
            'updated_by' => UpdatedByField::class,
            'editor'     => EditorField::class,
        ];
    }

}