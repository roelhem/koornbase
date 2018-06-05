<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 05-06-18
 * Time: 16:54
 */

namespace App\Http\Requests\Api;


use App\Contracts\Finders\FinderCollection;
use App\Person;

trait PersonFromInputData
{

    /**
     * Picks the person that was referenced in the input data of this request.
     *
     * @param array|null $inputData
     * @param string     $attributeName
     * @return Person|null
     */
    public function getPersonFromInput($inputData = null, $attributeName = 'person') {
        if($inputData === null) {
            $personInput = $this->get($attributeName);
        } else {
            $personInput = array_get($inputData, $attributeName);
        }

        try {
            $finders = resolve(FinderCollection::class);
            $person = $finders->find($personInput, 'person');

            if($person instanceof Person) {
                return $person;
            } else {
                return null;
            }
        } catch (\Exception $exception) {
            return null;
        }
    }

}