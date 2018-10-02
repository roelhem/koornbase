<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 08:26
 */

namespace App\Enums;


use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberUtil;
use MabeEnum\Enum;
use libphonenumber\PhoneNumberFormat as LibPhoneNumberFormat;

/**
 * Class PhoneNumberFormat
 * @package App\Enums
 *
 * @method static PhoneNumberFormat E164()
 * @method static PhoneNumberFormat INTERNATIONAL()
 * @method static PhoneNumberFormat NATIONAL()
 * @method static PhoneNumberFormat RFC3966()
 * @method static PhoneNumberFormat FOR_FIXED()
 * @method static PhoneNumberFormat FOR_MOBILE()
 * @method static PhoneNumberFormat FOR_MOBILE_COMPACT()
 */
final class PhoneNumberFormat extends Enum
{

    const E164 = LibPhoneNumberFormat::E164;
    const INTERNATIONAL = LibPhoneNumberFormat::INTERNATIONAL;
    const NATIONAL = LibPhoneNumberFormat::NATIONAL;
    const RFC3966 = LibPhoneNumberFormat::RFC3966;
    const FOR_FIXED = 'FOR_FIXED';
    const FOR_MOBILE = 'FOR_MOBILE';
    const FOR_MOBILE_COMPACT = 'FOR_MOBILE_COMPACT';

    public static function default()
    {
        return self::FOR_MOBILE();
    }

    /** @var PhoneNumberUtil */
    protected static $utils;

    /**
     * @return PhoneNumberUtil
     */
    protected static function utils() {
        if(self::$utils === null) {
            self::$utils = resolve(PhoneNumberUtil::class);
        }
        return self::$utils;
    }

    public function format(PhoneNumber $phoneNumber, $countryCode = 'NL')
    {
        switch ($this->getValue()) {
            case self::FOR_FIXED: return self::utils()->formatOutOfCountryCallingNumber($phoneNumber, $countryCode);
            case self::FOR_MOBILE: return self::utils()->formatNumberForMobileDialing($phoneNumber, $countryCode, true);
            case self::FOR_MOBILE_COMPACT: return self::utils()->formatNumberForMobileDialing($phoneNumber, $countryCode, false);
            case self::E164: return self::utils()->format($phoneNumber, $this->getValue());
            case self::INTERNATIONAL: return self::utils()->format($phoneNumber, $this->getValue());
            case self::NATIONAL: return self::utils()->format($phoneNumber, $this->getValue());
            case self::RFC3966: return self::utils()->format($phoneNumber, $this->getValue());
        }
    }
}