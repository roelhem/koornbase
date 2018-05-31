<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 30-05-18
 * Time: 21:34
 */

namespace App\Traits\PersonContactEntry;
use CommerceGuys\Addressing\Repository\CountryRepositoryInterface;

/**
 * Trait HasCountryCode
 *
 * @package App\Traits\PersonContactEntry
 *
 * @property string $country_code
 *
 * @property-read string $country
 */
trait HasCountryCode
{

    /**
     * Returns the country_code value, or a default value if the value is not set yet.
     *
     * @param $value
     * @return string
     */
    public function getCountryCodeAttribute($value) {
        if($value === null) {
            return 'NL';
        } else {
            return $value;
        }
    }

    /**
     * Saves the country_code in the right format.
     *
     * @param $newValue
     */
    public function setCountryCodeAttribute($newValue) {
        $this->attributes['country_code'] = mb_strtoupper(substr(trim($newValue),'0','2'));
    }

    /**
     * Returns the full, Dutch name of the country.
     *
     * @return mixed
     */
    public function getCountryAttribute() {
        $code = $this->country_code;
        $list = app(CountryRepositoryInterface::class)->getList('NL');
        return array_get($list, $code);
    }

}