<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 17:20
 */

namespace App\Types;


use App\Enums\PersonNameFormat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Fluent;

/**
 * Class Name
 * @package App\Types
 *
 * @property string      $name_first
 * @property string|null $name_middle
 * @property string|null $name_prefix
 * @property string      $name_last
 * @property string|null $name_initials
 * @property string|null $name_nickname
 */
class Name extends Fluent
{
    public static $fields = ['name_first', 'name_middle', 'name_prefix', 'name_last', 'name_initials', 'name_nickname'];

    public function __construct($attributes = [])
    {
        $attrs = [];
        if(is_array($attributes)) {
            $attrs = array_only($attributes, self::$fields);
        } elseif ($attributes instanceof Model) {
            $attrs = $attributes->only(self::$fields);
        }


        parent::__construct($attrs);
    }

    /**
     * @param PersonNameFormat $format
     * @return string
     */
    public function format($format) {
        $format = PersonNameFormat::get($format);
        return $format->format($this);
    }
}