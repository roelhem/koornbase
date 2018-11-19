<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 07:27
 */

namespace App\Http\GraphQL\Types;


use App\PersonAddress;
use App\Types\Name;
use CommerceGuys\Addressing\AddressInterface;
use CommerceGuys\Addressing\Country\CountryRepositoryInterface;
use CommerceGuys\Addressing\Formatter\FormatterInterface;
use Roelhem\GraphQL\Facades\GraphQL;
use Roelhem\GraphQL\Types\ObjectType;

class PostalAddressType extends ObjectType
{

    public $name = 'PostalAddress';

    public $description = 'The `PostalAddress` models addresses that can be used to send e-mails to, or to use as an
                           address for sending invoices.';

    protected function fields()
    {
        return [
            'country' => [
                'description' => "The `Country` of the address.
                                  \n\nThe value of this field largely determines the format in which the address 
                                  should be displayed to the user.",
                'type' => GraphQL::type('Country'),
                'resolve' => function(AddressInterface $address) {
                    /** @var CountryRepositoryInterface $repository */
                    $repository = resolve(CountryRepositoryInterface::class);
                    return $repository->get($address->getCountryCode());
                },
                'importance' => 100,
            ],
            'administrativeArea' => [
                'description' => "The name of the 'Administrative Subdivision-Area' where this address is located.
                                  \n\nThe proper name of these subdivisions differ per Country. Some examples for
                                  what this is called in different countries:
                                  \n
                                  \n - *province* in **The Netherlands**, **France**, **Italy**
                                  \n - *state* in **United States**
                                  \n - *county* in **Great Britain**
                                  \n - *prefecture* in **Japan**",
                'type' => GraphQL::type('String'),
                'resolve' => function(AddressInterface $address) {
                    return $address->getAdministrativeArea();
                },
            ],
            'locality' => [
                'description' => "Field for the 'locality' of the address, which will contain the **Name of the City** 
                                  for most of the cases.
                                  \n\n Be aware that there are some countries that do not need to specify this field.
                                  For most of those countries, this infomation is written in the 'addressLine1'-field
                                  instead.",
                'type' => GraphQL::type('String'),
                'resolve' => function(AddressInterface $address) {
                    return $address->getLocality();
                }
            ],
            'dependentLocality' => [
                'description' => "Field for the 'dependent locality' of this address. In most cases, this will be the
                                  name of the **neighbourhood** of the address.
                                  \n\n In cases where there is more then one dependent locality
                                  (like some places in Great Britain), all dependent localities should be written in
                                  this field, separated with a `, `. For example: \"Whaley, Langwith\".",
                'type' => GraphQL::type('String'),
                'resolve' => function(AddressInterface $address) {
                    return $address->getDependentLocality();
                }
            ],
            'postalCode' => [
                'description' => "Gets the 'postal code' for this address. The format is different for every country,
                                  but the server tries to validate that the value is valid.",
                'type' => GraphQL::type('String'),
                'resolve' => function(AddressInterface $address) {
                    return $address->getPostalCode();
                }
            ],
            'sortingCode' => [
                'description' => "Field for the 'sorting code' for this address. (for example, *CEDEX* in **France**)",
                'type' => GraphQL::type('String'),
                'resolve' => function(AddressInterface $address) {
                    return $address->getSortingCode();
                }
            ],
            'addressLine1' => [
                'description' => "The value of the first line in a address-block. This will usually contain detailed
                                  location information (like *streetname and house number*).",
                'type' => GraphQL::type('String'),
                'resolve' => function(AddressInterface $address) {
                    return $address->getAddressLine1();
                }
            ],
            'addressLine2' => [
                'description' => "The value of the second line in a address-block. This line is sometimes required to
                                  be added to an address, with very different information. (in most cases, it contains
                                  *the number of the apartment* in a big building.)",
                'type' => GraphQL::type('String'),
                'resolve' => function(AddressInterface $address) {
                    return $address->getAddressLine2();
                }
            ],
            'organisation' => [
                'description' => "An row that specifies the name of the organisation at this address.",
                'type' => GraphQL::type('String'),
                'resolve' => function(AddressInterface $address) {
                    return $address->getOrganization();
                },
                'importance' => -1,
            ],
            'name' => [
                'description' => "The name of the recipient at this address.",
                'type' => GraphQL::type('PersonName'),
                'resolve' => function(AddressInterface $address) {
                    if($address instanceof PersonAddress) {
                        return new Name($address->person);
                    } else {
                        return new Name([
                            'name_first' => $address->getGivenName(),
                            'name_prefix' => $address->getAdditionalName(),
                            'name_last' => $address->getFamilyName(),
                        ]);
                    }
                },
                'importance' => -2,
            ],
            'format' => [
                'description' => 'Gives a `String` that displays the address according to the proper rules of the region of the address.',
                'type' => GraphQL::type('String'),
                'args' => [
                    'html' => [
                        'description' => 'Set this argument to true to return the formatted address with Html-tags.',
                        'type' => GraphQL::type('Boolean'),
                        'default' => false,
                    ],
                    'locale' => [
                        'description' => 'Set the locale in which the address should be formatted.',
                        'type' => GraphQL::type('Locale'),
                        'default' => config('app.addressing_locale'),
                    ],
                ],
                'resolve' => function(AddressInterface $address, $args) {
                    /** @var FormatterInterface $formatter */
                    $formatter = resolve(FormatterInterface::class);
                    return $formatter->format($address,[
                        'html' => array_get($args,'html',false),
                        'locale' => array_get($args, 'locale','nl'),
                    ]);
                },
                'importance' => 200,
            ]
        ];
    }

}