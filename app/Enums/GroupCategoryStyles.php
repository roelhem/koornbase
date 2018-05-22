<?php

namespace App\Enums;

use BenSampo\Enum\Enum;
use PhpParser\Node\Expr\BinaryOp\BooleanOr;

final class GroupCategoryStyles extends Enum
{
    const Default = 0;
    const System = 1;
    const Master = 2;
    const Debug = 3;
    const Develop = 4;
    const Important = 5;
    const Boss = 6;
    const Structure = 7;
    const Primary = 8;
    const Secondary = 9;
    const Skill = 10;
    const Friend = 11;
    const Tag = 12;
    const Study = 13;

    /**
     * Returns the dutch name of every style.
     *
     * @param int $value
     * @return string
     */
    public static function getDutchName(int $value): string
    {
        switch ($value) {
            case self::Default:
                return 'Standaard';
            case self::System:
                return 'Systeem';
            case self::Master:
                return 'Webmaster';
            case self::Debug:
                return 'Debug';
            case self::Develop:
                return 'Ontwikkelen';
            case self::Important:
                return 'Belangrijk';
            case self::Boss:
                return 'Boss';
            case self::Structure:
                return 'Structureel';
            case self::Primary:
                return 'Primair';
            case self::Secondary:
                return 'Secundair';
            case self::Skill:
                return 'Vaardigheid';
            case self::Friend:
                return 'Vriend';
            case self::Tag:
                return 'Label';
            case self::Study:
                return 'Studie';
            default:
                return '(Onbekend)';
        }
    }

    /**
     * Returns an array that describes all the parameters of the style.
     *
     * @param int $value
     * @return array
     */
    public static function getStyle(int $value): array
    {
        return [
            'id' => $value,
            'color' => self::getColor($value),
            'tagColor' => self::getTagColor($value),
            'avatar' => [
                'color' => self::getAvatarColor($value),
                'icon' => ['fa', 'fa-'.self::getAvatarIcon($value)]
            ],
        ];
    }

    /**
     * Returns the main color (from BoostrapColors) of the given GroupCategoryStyle.
     *
     * @param int $value
     * @return string
     */
    public static function getColor(int $value): string
    {
        switch ($value) {
            case self::System: case self::Debug: case self::Develop:
                return BootstrapColors::DarkGray;
            case self::Master:
                return BootstrapColors::Purple;
            case self::Important: case self::Boss:
                return BootstrapColors::Red;
            case self::Structure:
                return BootstrapColors::Pink;
            case self::Primary:
                return BootstrapColors::Orange;
            case self::Secondary:
                return BootstrapColors::Yellow;
            case self::Skill:
                return BootstrapColors::Green;
            case self::Tag:
                return BootstrapColors::Lime;
            case self::Friend:
                return BootstrapColors::Teal;
            case self::Study:
                return BootstrapColors::Indigo;
            case self::Default:
            default:
                return BootstrapColors::Gray;
        }
    }

    /**
     * Returns the color of the avatar image of a tag.
     *
     * @param int $value
     * @return string
     */
    public static function getAvatarColor(int $value): string
    {
        return self::getColor($value);
    }

    /**
     * Returns the color of the tag.
     *
     * @param int $value
     * @return string|null
     */
    public static function getTagColor(int $value)
    {
        switch ($value) {
            case self::Debug:
                return BootstrapColors::Gray;
            case self::System:
            case self::Master:
                return self::getColor($value);
            default:
                return null;
        }
    }

    /**
     * Return the icon that functions as the avatar placeholder.
     *
     * @param int $value
     * @return string
     */
    public static function getAvatarIcon(int $value): string
    {
        switch ($value) {
            case self::System:
                return 'server';
            case self::Master:
                return 'terminal';
            case self::Debug:
                return 'bug';
            case self::Develop:
                return 'code';
            case self::Important:
                return 'sun';
            case self::Boss:
                return 'user-tie';
            case self::Structure:
                return 'cubes';
            case self::Primary: case self::Secondary:
                return 'puzzle-piece';
            case self::Skill:
                return 'support';
            case self::Friend:
                return 'beer';
            case self::Tag:
                return 'tag';
            case self::Study:
                return 'graduation-cap';
            default:
                return 'users';
        }
    }

}
