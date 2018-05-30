<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 30-04-18
 * Time: 01:14
 */

namespace App\Traits;


/**
 * Trait HasShortName
 *
 * This trait should be added to every Model that has the name_short attribute.
 *
 * @package App\Traits
 *
 * @property string $name
 * @property string $name_short
 */
trait HasShortName
{

    /**
     * If the NameShort is not set, return the default name.
     *
     * @param string|null $value
     * @return string
     */
    public function getNameShortAttribute($value) {
        if(empty($value)) {
            return $this->name;
        } else {
            return $value;
        }
    }

}