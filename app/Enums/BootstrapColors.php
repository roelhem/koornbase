<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class BootstrapColors extends Enum
{
    const Blue = 'blue';
    const Azure = 'azure';
    const Indigo = 'indigo';
    const Teal = 'teal';
    const Cyan = 'cyan';
    const Purple = 'purple';
    const Pink = 'pink';
    const Red = 'red';
    const Orange = 'orange';
    const Yellow = 'yellow';
    const Lime = 'lime';
    const Green = 'green';
    const Gray = 'gray';
    const DarkGray = 'gray-dark';
    const Dark = 'dark';
    const Light = 'light';

    /**
     * Get .bg-* bootstrap class.
     *
     * @param  string  $value
     * @return string
     */
    public static function getBackgroundClass(string $value): string
    {
        return 'bg-'.$value;
    }

    /**
     * Get .bg-* bootstrap class.
     *
     * @param  string  $value
     * @return string
     */
    public static function getTextClass(string $value): string
    {
        switch ($value) {
            case self::Gray: return 'text-muted';
            case self::DarkGray: return 'text-muted-dark';
            default: return 'text-'.$value;
        }

    }

    /**
     * Gets an string that can be used as a color in an css statement.
     *
     * @param string $value
     * @return string
     */
    public static function getCssVar(string $value):string
    {
        return "var(--{$value})";
    }

    /**
     * Returns the dutch name of the color.
     */
    public static function getDutchName(string $value): string
    {
        switch ($value) {
            case self::Blue:
                return 'Blauw';
            case self::Azure:
                return 'Azuur';
            case self::Indigo:
                return 'Indigo';
            case self::Teal:
                return 'Turquoise';
            case self::Cyan:
                return 'Cyaan';
            case self::Purple:
                return 'Paars';
            case self::Pink:
                return 'Roze';
            case self::Red:
                return 'Rood';
            case self::Orange:
                return 'Oranje';
            case self::Yellow:
                return 'Geel';
            case self::Lime:
                return 'Limoen';
            case self::Green:
                return 'Groen';
            case self::Gray:
                return 'Grijs';
            case self::DarkGray:
                return 'Donkergrijs';
            case self::Dark:
                return 'Donker';
            case self::Light:
                return 'Licht';
            default:
                return '(Onbekend)';
        }
    }
}
