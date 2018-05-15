<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class EventVisibility extends Enum
{
    const HIDDEN = 0;
    const MODERATOR = 1;
    const CREATOR = 2;
    const MANAGER = 3;
    const BESTUUR = 4;

    /**
     * Get the description for an enum value
     *
     * @param  int  $value
     * @return string
     */
    public static function getDescription(int $value): string
    {
        switch ($value) {
            case self::OptionOne:
                return 'Option one';
            break;
            default:
                return self::getKey($value);
        }
    }
}
