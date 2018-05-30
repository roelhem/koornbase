<?php

namespace App\Enums;

use App\Person;
use BenSampo\Enum\Enum;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Class MembershipStatus
 *
 * The different membership status that a Person can have.
 *
 * @package App\Enums
 */
final class MembershipStatus extends Enum
{
    const Outsider = 0;
    const Novice = 1;
    const Member = 2;
    const FormerMember = 3;

    public static function getBackgroundClass(int $value) {
        switch ($value) {
            case self::Outsider: return 'bg-secondary';
            case self::Novice: return 'bg-warning';
            case self::Member: return 'bg-success';
            case self::FormerMember: return 'bg-danger';
            default: return 'bg-light';
        }
    }

    public static function getLabel(int $value) {
        switch ($value) {
            case self::Outsider: return 'Buitenstaander';
            case self::Novice: return 'Kennismaker';
            case self::Member: return 'Lid';
            case self::FormerMember: return 'Oud-Lid';
            default: return 'Onbekend';
        }
    }
}
