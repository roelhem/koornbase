<?php

namespace Tests\Feature\Validators;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddressValidatorTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCountryCode()
    {

        $this->assertTrue(\Validator::make(['a' => 'NL'],['a' => 'country_code'])->passes());
        $this->assertTrue(\Validator::make(['a' => 'FR'],['a' => 'country_code'])->passes());
        $this->assertTrue(\Validator::make(['a' => 'BE'],['a' => 'country_code'])->passes());

        $this->assertFalse(\Validator::make(['a' => 'AA'],['a' => 'country_code'])->passes());
        $this->assertFalse(\Validator::make(['a' => 1],['a' => 'country_code'])->passes());
        $this->assertFalse(\Validator::make(['a' => null],['a' => 'country_code'])->passes());
        $this->assertFalse(\Validator::make(['a' => 'fdsafgdsagfsda'],['a' => 'country_code'])->passes());

    }


    /**
     * Tests the address_field rule in a minimal way
     */
    public function testSimpleAddressField()
    {

        // Simple passing
        $this->assertTrue(\Validator::make(
            [
                'locality' => "Delft",
                'postal_code' => '2611 EW',
                'address_line_1' => 'De Vlouw 1D'
            ],
            [
                'locality' => 'address_field',
                'postal_code' => 'address_field',
                'address_line_1' => 'address_field'
            ]
        )->passes());

        // Simple failing.
        $this->assertFalse(\Validator::make(
            [
            ],
            [
                'locality' => 'address_field',
                'postal_code' => 'address_field',
                'address_line_1' => 'address_field'
            ]
        )->passes());
    }

    /**
     * Provides address_fields to test
     *
     * @return array
     */
    public function addressProvider() {
        return [
            'EMPTY_ADDRESS' => [
                [],
                ['address_line_1','locality','postal_code']
            ],
            'DUTCH_ADDRESS' => [
                [
                    'locality' => 'Delft',
                    'postal_code' => '2391 EH',
                    'address_line_1' => 'De Vlouw 1D',
                    'address_line_2' => 'In de binnenstad',
                    'organization' => 'Huize Kebkeenbaan'
                ],
                []
            ],
            'US_ADDRESS_INVALID' => [
                [
                    'country_code' => 'US',
                    'locality' => 'Delft',
                    'postal_code' => '2391 EH',
                    'address_line_1' => 'De Vlouw 1D',
                    'address_line_2' => 'In de binnenstad',
                    'organization' => 'Huize Kebkeenbaan'
                ],
                ['administrative_area']
            ],
            'US_ADDRESS_VALID' => [
                [
                    'country_code' => 'US',
                    'locality' => 'Delft',
                    'postal_code' => '2391 EH',
                    'address_line_1' => 'De Vlouw 1D',
                    'address_line_2' => 'In de binnenstad',
                    'organization' => 'Huize Kebkeenbaan',
                    'administrative_area' => 'NY'
                ],
                []
            ],
            'US_ADDRESS_IN_NL' => [
                [
                    'locality' => 'Delft',
                    'postal_code' => '2391 EH',
                    'address_line_1' => 'De Vlouw 1D',
                    'address_line_2' => 'In de binnenstad',
                    'organization' => 'Huize Kebkeenbaan',
                    'administrative_area' => 'NY'
                ],
                ['administrative_area']
            ]
        ];
    }

    /**
     * Tests the address_field rule with a provider.
     *
     * @param array $address,
     * @param array $expectedErrors
     * @dataProvider addressProvider
     */
    public function testAddressField($address, $expectedErrors) {
        $validator = \Validator::make($address, [
            'country_code' => 'country_code',
            'administrative_area' => 'address_field',
            'locality' => 'address_field',
            'dependent_locality' => 'address_field',
            'postal_code' => 'address_field',
            'sorting_code' => 'address_field',
            'address_line_1' => 'address_field',
            'address_line_2' => 'address_field',
            'organisation' => 'address_field'
        ]);


        if (count($expectedErrors) === 0) {
            $this->assertTrue($validator->passes(), $validator->errors());
        } else {
            $this->assertTrue($validator->fails(), $validator->errors());
        }

        $errors = $validator->errors();
        foreach ($expectedErrors as $expectedError) {
            $this->assertTrue($errors->has($expectedError));
        }

    }

    /**
     * Provides postal_codes to test.
     *
     * @return array
     */
    public function postalCodePovider() {
        return [
            'DUTCH_DEFAULT' => [
                ['postal_code' => '2391 EH'],
                true
            ],
            'DUTCH_VARIATION' => [
                ['postal_code' => '2611ew'],
                true
            ],
            'DUTCH_WRONG' => [
                ['postal_code' => 'fdsafdsa'],
                false
            ],
            'EMPTY' => [
                [],
                true
            ],
            'GERMAN_DEFAULT' => [
                ['country_code' => 'DE','postal_code' => '12635'],
                true,
            ],
            'GERMAN_WRONG' => [
                ['country_code' => 'DE','postal_code' => 'afdsg'],
                false,
            ],
            'GERMAN_UNTRIMMED' => [
                ['country_code' => 'DE','postal_code' => '  35423 '],
                false,
            ]
        ];
    }

    /**
     * @param array $values
     * @param boolean $expected
     * @dataProvider  postalCodePovider
     */
    public function testPostalCode($values, $expected) {
        $validator = \Validator::make($values, [
            'postal_code' => 'postal_code',
            'country_code' => 'nullable|country_code'
        ]);

        $this->assertEquals($expected, $validator->passes());
    }

    public function testAllowUnusedOption()
    {
        $validatorWithout = \Validator::make(
            ['administrative_area' => 'hallo'],
            ['administrative_area' => 'address_field']
        );

        $validatorWith = \Validator::make(
            ['administrative_area' => 'hallo'],
            ['administrative_area' => 'address_field:allow_unused']
        );

        $this->assertFalse($validatorWithout->passes());
        $this->assertTrue($validatorWith->passes());
    }

}
