<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 08:39
 */

namespace App\Enums;


use MabeEnum\Enum;
use libphonenumber\PhoneNumberType as LibPhoneNumberType;

/**
 * Class PhoneNumberType
 * @package App\Enums
 *
 * @method static PhoneNumberType FIXED_LINE()
 * @method static PhoneNumberType MOBILE()
 * @method static PhoneNumberType FIXED_LINE_OR_MOBILE()
 * @method static PhoneNumberType TOLL_FREE()
 * @method static PhoneNumberType PREMIUM_RATE()
 * @method static PhoneNumberType SHARED_COST()
 * @method static PhoneNumberType VOIP()
 * @method static PhoneNumberType PERSONAL_NUMBER()
 * @method static PhoneNumberType PAGER()
 * @method static PhoneNumberType UAN()
 * @method static PhoneNumberType UNKNOWN()
 * @method static PhoneNumberType EMERGENCY()
 * @method static PhoneNumberType VOICEMAIL()
 * @method static PhoneNumberType SHORT_CODE()
 * @method static PhoneNumberType STANDARD_RATE()
 */
final class PhoneNumberType extends Enum
{
    const FIXED_LINE = LibPhoneNumberType::FIXED_LINE;
    const MOBILE = LibPhoneNumberType::MOBILE;
    // In some regions (e.g. the USA), it is impossible to distinguish between fixed-line and
    // mobile numbers by looking at the phone number itself.
    const FIXED_LINE_OR_MOBILE = LibPhoneNumberType::FIXED_LINE_OR_MOBILE;
    // Freephone lines
    const TOLL_FREE = LibPhoneNumberType::TOLL_FREE;
    const PREMIUM_RATE = LibPhoneNumberType::PREMIUM_RATE;
    // The cost of this call is shared between the caller and the recipient, and is hence typically
    // less than PREMIUM_RATE calls. See // http://en.wikipedia.org/wiki/Shared_Cost_Service for
    // more information.
    const SHARED_COST = LibPhoneNumberType::SHARED_COST;
    // Voice over IP numbers. This includes TSoIP (Telephony Service over IP).
    const VOIP = LibPhoneNumberType::VOIP;
    // A personal number is associated with a particular person, and may be routed to either a
    // MOBILE or FIXED_LINE number. Some more information can be found here:
    // http://en.wikipedia.org/wiki/Personal_Numbers
    const PERSONAL_NUMBER = LibPhoneNumberType::PERSONAL_NUMBER;
    const PAGER = LibPhoneNumberType::PAGER;
    // Used for "Universal Access Numbers" or "Company Numbers". They may be further routed to
    // specific offices, but allow one number to be used for a company.
    const UAN = LibPhoneNumberType::UAN;
    // A phone number is of type UNKNOWN when it does not fit any of the known patterns for a
    // specific region.
    const UNKNOWN = LibPhoneNumberType::UNKNOWN;

    // Emergency
    const EMERGENCY = LibPhoneNumberType::EMERGENCY;

    // Voicemail
    const VOICEMAIL = LibPhoneNumberType::VOICEMAIL;

    // Short Code
    const SHORT_CODE = LibPhoneNumberType::SHORT_CODE;

    // Standard Rate
    const STANDARD_RATE = LibPhoneNumberType::STANDARD_RATE;
}