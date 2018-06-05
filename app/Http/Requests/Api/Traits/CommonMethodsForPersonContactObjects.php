<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 05-06-18
 * Time: 21:04
 */

namespace App\Http\Requests\Api\Traits;


use Illuminate\Database\Eloquent\Builder;

trait CommonMethodsForPersonContactObjects
{

    /**
     * Checks if the given label does exist in the given query.
     *
     * An id can be provided that has to be excluded from the labels to check.
     *
     * Returns the value of $default if the given $label parameter is null.
     *
     * @param string|null $label
     * @param Builder $query
     * @param integer|null $exclude_id
     * @param bool $default
     * @return bool
     */
    protected function labelExists($label, $query, $exclude_id = null, $default = false)
    {
        if($label === null) {
            return $default;
        }

        $whereParams = [];
        $whereParams[] = ['label','=',trim($label)];
        if($exclude_id !== null) {
            $whereParams[] = ['id','<>',$exclude_id];
        }

        return $query->where($whereParams)->exists();
    }

    /**
     * Some store-rules that are shared by all PersonContactObjects
     *
     * @return array
     */
    protected function contactDefaultStoreRules() {
        return [
            'person' => 'required|finds:person',
            'label' => 'required|string|max:63',
            'index' => 'integer|nullable',
            'options' => 'array',
            'remarks' => 'nullable|text',
        ];
    }

    /**
     * Some update-rules that are shared by all PersonContactObjects
     *
     * @return array
     */
    protected function contactDefaultUpdateRules() {
        return [
            'label' => 'sometimes|required|string|max:63',
            'index' => 'integer|nullable',
            'options' => 'array',
            'remarks' => 'nullable|text',
        ];
    }

}