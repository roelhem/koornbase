<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 13-05-18
 * Time: 23:51
 */

namespace App\Traits;
use App\Types\OptionsType;
use Illuminate\Database\Eloquent\Builder;

/**
 * Trait HasOptions
 *
 * This trait helps with the management of the `options` jsonb column.
 *
 * This trait should be added to every model that have an `options` column of the type jsonb.
 *
 * @package App\Traits
 *
 * @property OptionsType $options
 */
trait HasOptions
{

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- DEFAULT OPTIONS ------------------------------------------------------------------------------------ //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * A method that returns an array with the default options of this Model.
     *
     * @return array
     */
    abstract protected function defaultOptions() : array;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- HELPER METHODS ------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Creates a new OptionsType instance for this Model.
     *
     * @param string|array|null $value
     * @return OptionsType
     * @throws \App\Exceptions\OptionNotFoundException
     */
    protected function createOptions($value = null)
    {
        $result = new OptionsType($this->defaultOptions(), $value, [$this, 'optionsChangeHandler']);
        return $result;
    }

    /**
     * @param OptionsType $optionsType
     */
    public function optionsChangeHandler($optionsType)
    {
        $this->attributes['options'] = $optionsType->toJson();
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- CUSTOM ACCESSORS ----------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns the options column as an OptionsType instance.
     *
     * @param string $value
     * @return OptionsType
     * @throws \App\Exceptions\OptionNotFoundException
     */
    public function getOptionsAttribute($value)
    {
        return $this->createOptions($value);
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- CUSTOM MUTATORS ------------------------------------------------------------------------------------ //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Sets the options attribute of this model.
     *
     * This attribute only accepts arrays, json-string, null-values or OptionsType. The keys of these object
     * must exist in the protected $defaultOptions property.
     *
     * @param $value
     * @throws \App\Exceptions\OptionNotFoundException
     */
    public function setOptionsAttribute($value)
    {
        if($value === null) {
            $this->attributes['options'] = null;
        } else {

            if(!($value instanceof OptionsType)) {
                $value = $this->createOptions($value);
            }

            $this->attributes['options'] = $value->toJson();
        }
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- SCOPES --------------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * A scope that gives the Models that have the options in the $options parameter.

     *
     * @param Builder $query
     * @param array $options
     * @return Builder
     */
    public function scopeWhereOptions($query, array $options)
    {
        $default_json = json_encode($this->defaultOptions(), JSON_FORCE_OBJECT);
        $json = json_encode($options, JSON_FORCE_OBJECT);

        return $query->whereRaw('?::jsonb || options @> ?', [$default_json, $json]);
    }

}