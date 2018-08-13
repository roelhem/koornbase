<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 11-07-18
 * Time: 11:01
 */

namespace App\GraphQL\Types;

use App\GraphQL\Fields\Authorization\ViewableField;
use App\GraphQL\Fields\CountryCodeField;
use App\GraphQL\Fields\CountryField;
use App\GraphQL\Fields\IdField;
use App\GraphQL\Fields\Relations\PersonField;
use App\GraphQL\Fields\Relations\PersonIdField;
use App\GraphQL\Fields\RemarksField;
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
            GraphQL::type('PersonContactEntry')
        ];
    }

    /** @inheritdoc */
    public function fields()
    {

        $ownedByPersonInterface = GraphQL::type('OwnedByPerson');
        $personContactEntryInterface = GraphQL::type('PersonContactEntry');

        return [
            'id' => IdField::class,
            $ownedByPersonInterface->getField('owner_id'),
            $ownedByPersonInterface->getField('owner'),

            'person_id' => PersonIdField::class,
            'person' => PersonField::class,
            $personContactEntryInterface->getField('index'),
            $personContactEntryInterface->getField('label'),

            'phone_number' => [
                'type' => Type::string(),
                'description' => 'The phone number.',
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
                },
                'always' => ['country_code']
            ],

            'country_code' => CountryCodeField::class,
            'country' => CountryField::class,

            'location' => [
                'type' => Type::string(),
                'description' => 'A description/approximation of the location based on the patterns in the phone number.',
                'resolve' => function(PersonPhoneNumber $root) {
                    return $this->geocoder->getDescriptionForNumber($root->phone_number, 'nl_NL');
                },
                'selectable' => false,
                'always' => ['country_code','phone_number']
            ],

            'number_type' => [
                'type' => GraphQL::type('PhoneNumberType'),
                'description' => 'The kind of phone number (based on the patterns in the phone number.)',
                'selectable' => false,
                'always' => ['country_code','phone_number']
            ],

            'remarks' => RemarksField::class,

            'created_at' => CreatedAtField::class,
            'created_by' => CreatedByField::class,
            'creator'    => CreatorField::class,
            'updated_at' => UpdatedAtField::class,
            'updated_by' => UpdatedByField::class,
            'editor'     => EditorField::class,


            'viewable' => ViewableField::class,
        ];
    }

}