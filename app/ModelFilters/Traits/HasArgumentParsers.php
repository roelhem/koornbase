<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 05-07-18
 * Time: 08:30
 */

namespace App\ModelFilters\Traits;


use App\Contracts\Finders\FinderCollection;
use Illuminate\Database\Eloquent\Model;

trait HasArgumentParsers
{

    /**
     * @param mixed $input
     * @return bool|null
     */
    protected function getBooleanValue($input) {

        if(is_bool($input)) {
            return $input;
        }
        if(is_integer($input)) {
            if($input === 0) {
                return false;
            } elseif($input === 1) {
                return true;
            } else {
                return null;
            }
        }

        if(is_string($input)) {
            $trueValues = ['1', 'true', 't', 'on', 'yes', 'y', 'ja', 'j', 'waar', 'aan'];
            $falseValues = ['0', 'false', 'f', 'off', 'no', 'n', 'nee', 'onwaar', 'uit'];

            $lowerCase = mb_strtolower($input);
            if (in_array($lowerCase, $trueValues)) {
                return true;
            } elseif (in_array($lowerCase, $falseValues)) {
                return false;
            } else {
                return null;
            }
        }
        return null;
    }

    /**
     * Returns the model instance referred by the input argument.
     *
     * @param mixed $input
     * @param $modelName
     * @return Model
     * @throws \App\Exceptions\Finders\InputNotAcceptedException
     * @throws \App\Exceptions\Finders\ModelNotFoundException
     */
    protected function getModelValue($input, $modelName) {
        /** @var FinderCollection $finders */
        $finders = resolve(FinderCollection::class);

        return $finders->find($input, $modelName);
    }

    /**
     * Returns the model id instance referred by the input argument.
     *
     * @param $input
     * @param $modelValue
     * @return int
     * @throws \App\Exceptions\Finders\InputNotAcceptedException
     * @throws \App\Exceptions\Finders\ModelNotFoundException
     */
    protected function getModelIdValue($input, $modelValue) {
        if(is_integer($input)) {
            return $input;
        } elseif(ctype_digit($input)) {
            return intval($input);
        } else {
            return $this->getModelValue($input, $modelValue)->getKey();
        }
    }

}