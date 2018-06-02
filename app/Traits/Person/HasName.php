<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 02-06-18
 * Time: 16:54
 */

namespace App\Traits\Person;

/**
 * Trait HasName
 *
 * Adds the functionality to work with names for a Person.
 *
 * @package App\Traits\Person
 *
 * @property string $name_first
 * @property string $name_last
 * @property string|null $name_middle
 * @property string|null $name_prefix
 * @property string|null $name_initials
 * @property string|null $name_nickname
 *
 * @property-read string $name          The most important parts of the name.
 * @property-read string $name_full     The full name of the person.
 * @property-read string $name_short    A short name that represents this person.
 * @property-read string $name_formal   The name of this person in a formal format.
 */
trait HasName
{

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- CUSTOM ACCESSORS ----------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns a string that represents the name of this person in a standard format.
     *
     * @return string
     */
    public function getNameAttribute() {
        $pieces = [];
        $pieces[] = $this->name_first;
        if($this->name_prefix !== null && !empty(trim($this->name_prefix))) {
            $pieces[] = $this->name_prefix;
        }
        $pieces[] = $this->name_last;

        return implode(' ', $pieces);
    }

    /**
     * Returns a string that represents the whole name of this person.
     *
     * @return string
     */
    public function getNameFullAttribute() {
        $pieces = [];
        $pieces[] = $this->name_first;
        if($this->name_middle !== null && !empty(trim($this->name_middle))) {
            $pieces[] = $this->name_middle;
        }
        if($this->name_prefix !== null && !empty(trim($this->name_prefix))) {
            $pieces[] = $this->name_prefix;
        }
        $pieces[] = $this->name_last;

        return implode(' ', $pieces);
    }

    /**
     * Returns the formal name of this person
     *
     * @return string
     */
    public function getNameFormalAttribute() {
        $pieces = [];
        $pieces[] = $this->name_initials;
        if($this->name_prefix !== null && !empty(trim($this->name_prefix))) {
            $pieces[] = $this->name_prefix;
        }
        $pieces[] = $this->name_last;

        return implode(' ', $pieces);
    }

    /**
     * Returns a short string that represents this person.
     *
     * @return string
     */
    public function getNameShortAttribute() {
        return empty($this->name_nickname) ? $this->name_first : $this->name_nickname;
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- CUSTOM MUTATORS ------------------------------------------------------------------------------------ //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Saves the initials in the right format.
     *
     * @param string $newValue
     * @throws
     */
    public function setNameInitialsAttribute($newValue) {
        $matches = [];

        if(empty($newValue)) {
            $this->attributes['name_initials'] = null;
            return;
        }

        $preg_match_result = preg_match_all('/[a-zA-Z]/', $newValue, $matches, PREG_PATTERN_ORDER);
        if($preg_match_result === false) {
            $error_number = preg_last_error();
            $error_name = array_flip(get_defined_constants(true)['pcre'])[$error_number];
            throw new \Exception("Een fout in het uitlezen van de regular expression. $error_number:$error_name");
        } elseif($preg_match_result > 0) {
            $res = '';
            foreach ($matches[0] as $match) {
                $res .= mb_strtoupper($match).'.';
            }
            $this->attributes['name_initials'] = $res;
        } else {
            $this->attributes['name_initials'] = null;
        }

    }

}