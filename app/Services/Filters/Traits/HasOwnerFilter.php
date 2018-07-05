<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 05-07-18
 * Time: 04:12
 */

namespace App\Services\Filters\Traits;
use App\Exceptions\Filters\FilterInvalidParametersException;
use App\Person;
use Illuminate\Database\Eloquent\Model;


/**
 * Trait HasOwnerFilter
 *
 * A trait that adds the owner filter to an filter provider.
 *
 * @package App\Services\Filters\Traits
 */
trait HasOwnerFilter
{

    /**
     * Parses a parameter that contains a reference to a model.
     *
     * @param mixed $param
     * @param string $modelName
     * @throws FilterInvalidParametersException
     * @return Model
     */
    protected abstract function parseModelParam($param, $modelName);

    /**
     * A filter that only passes the models that are owned by owner form the parameter.
     *
     * @param mixed $params
     * @return \Closure
     * @throws FilterInvalidParametersException
     */
    public function filterOwner($params) {

        $owner = $this->parseModelParam($params, 'person');

        if($owner instanceof Person) {
            $id = $owner->id;
            return function($query) use ($id) {
                return $query->ownedBy($id);
            };
        } else {
            throw new \LogicException("Found model has the wrong type.");
        }
    }

}