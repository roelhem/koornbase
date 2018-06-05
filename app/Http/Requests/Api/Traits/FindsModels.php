<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 05-06-18
 * Time: 20:44
 */

namespace App\Http\Requests\Api\Traits;


use App\Contracts\Finders\FinderCollection;
use Illuminate\Database\Eloquent\Model;

trait FindsModels
{

    /**
     * Finds a model using the FinderCollection
     *
     * @param string $name
     * @param mixed $fromValue
     * @return Model|null
     */
    public function findModel($name, $fromValue) {
        $finders = resolve(FinderCollection::class);

        try {
            $result = $finders->find($fromValue, $name);
            if($result instanceof Model) {
                return $result;
            } else {
                return null;
            }
        } catch (\Exception $exception) {
            return null;
        }
    }

    /**
     * Finds a model with FinderCollection from a url parameter.
     *
     * @param string $name
     * @param string|null $varName
     * @return Model|null
     */
    public function findFromUrl($name, $varName = null) {
        if($varName === null) {
            $varName = $name;
        }
        return $this->findModel($name, $this->route($varName));
    }

    /**
     * Finds a model with FinderCollection from the input values
     *
     * @param string $name
     * @param array|null $data
     * @param string|null $attributeName
     * @return Model|null
     */
    public function findFromInput($name, $data = null, $attributeName = null) {
        if($attributeName === null) {
            $attributeName = $name;
        }

        if($data === null) {
            return $this->findModel($name, $this->get($attributeName));
        } else {
            return $this->findModel($name, array_get($data, $attributeName));
        }
    }

}