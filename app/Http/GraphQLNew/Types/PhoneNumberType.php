<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 08:14
 */

namespace App\Http\GraphQLNew\Types;


use App\Enums\PhoneNumberFormat;
use CommerceGuys\Addressing\Country\CountryRepositoryInterface;
use libphonenumber\geocoding\PhoneNumberOfflineGeocoder;
use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberToCarrierMapper;
use libphonenumber\PhoneNumberUtil;
use Roelhem\GraphQL\Facades\GraphQL;
use Roelhem\GraphQL\Types\ObjectType;

class PhoneNumberType extends ObjectType
{

    public $name = 'PhoneNumber';

    public $description = 'The type `PhoneNumber` represents a phone-number that can be represented in several 
                           different formats. Furthermore, it is possible to retrieve some extra information about 
                           the phone number based on patterns in the phone-number itself.';

    protected $util;
    protected $geocoder;

    public function __construct(PhoneNumberUtil $util, PhoneNumberOfflineGeocoder $geocoder, array $config = [])
    {
        $this->util = $util;
        $this->geocoder = $geocoder;
        parent::__construct($config);
    }

    protected function fields()
    {
        return [
            'type' => [
                'type' => GraphQL::type('PhoneNumberType'),
                'description' => 'The type of this `PhoneNumber`. This value is determined based on the patterns in the phone-number itself.',
                'resolve' => function(PhoneNumber $phoneNumber) {
                    return \App\Enums\PhoneNumberType::get($this->util->getNumberType($phoneNumber));
                }
            ],
            'number' => [
                'description' => 'A `String`-representation of the `PhoneNumber` in this field.',
                'type' => GraphQL::type('String'),
                'args' => [
                    'format' => [
                        'description' => 'Optional argument that can be used to set the format in which you want to represent the `PhoneNumber`.',
                        'type' => GraphQL::type('PhoneNumberFormat'),
                        'default' => PhoneNumberFormat::default(),
                    ],
                    'from' => [
                        'description' => 'The code of a region. The phone number will be formatted to enable calls from the specified region.',
                        'type' => GraphQL::type('CountryCode'),
                        'default' => 'NL',
                    ]
                ],
                'resolve' => function(PhoneNumber $phoneNumber, $args) {
                    /** @var PhoneNumberFormat $format */
                    $format = array_get($args, 'format', PhoneNumberFormat::default());
                    return $format->format($phoneNumber, array_get($args, 'from','NL'));
                }
            ],
            'location' => [
                'description' => 'An approximation for where the `PhoneNumber` is located. This value will be determined based on the patterns in the phone-number itself.',
                'type' => GraphQL::type('String'),
                'args' => [
                    'locale' => [
                        'description' => 'The locale in which to display the location of this PhoneNumber',
                        'type' => GraphQL::type('Locale'),
                        'default' => 'nl_NL',
                    ]
                ],
                'resolve' => function(PhoneNumber $phoneNumber, $args) {
                    return $this->geocoder->getDescriptionForNumber($phoneNumber, array_get($args,'locale','nl_NL'));
                }
            ],
            'country' => [
                'description' => 'The country where the `PhoneNumber` is registered.',
                'type' => GraphQL::type('Country'),
                'resolve' => function(PhoneNumber $phoneNumber) {
                    /** @var CountryRepositoryInterface $repository */
                    $repository = resolve(CountryRepositoryInterface::class);
                    return $repository->get($this->util->getRegionCodeForNumber($phoneNumber));
                }
            ],
            'link' => [
                'description' => 'Returns a string that can be used as a link on mobile phones. When clicked on this linked, the phone-number of the current object will be called by the user.',
                'type' => GraphQL::type('URL'),
                'resolve' => function(PhoneNumber $phoneNumber) {
                    $res = $this->util->format($phoneNumber,PhoneNumberFormat::RFC3966);
                    return str_replace('tel:','tel://',$res);
                }
            ],
        ];
    }

}