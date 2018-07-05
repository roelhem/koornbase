<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 03-07-18
 * Time: 16:10
 */

namespace App\Services\Filters;
use App\Contracts\Filters\FilterProvider;
use App\Contracts\Finders\FinderCollection;
use App\Exceptions\Filters\FilterInvalidParametersException;
use App\Exceptions\Filters\FilterNotFoundException;
use App\Exceptions\Finders\InputNotAcceptedException;
use App\Exceptions\Finders\ModelNotFoundException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


/**
 * Class AbstractFilterProvider
 *
 * An abstract implementation of the FilterProvider Contract.
 *
 * @package App\Services\Filters
 */
abstract class AbstractFilterProvider implements FilterProvider
{


    /**
     * @var FinderCollection
     */
    protected $finderCollection;

    /**
     * AbstractFilterProvider constructor.
     *
     *
     * @param FinderCollection $finderCollection
     */
    public function __construct(FinderCollection $finderCollection)
    {
        $this->finderCollection = $finderCollection;
    }




    // ---------------------------------------------------------------------------------------------------------- //
    // ----- IMPLEMENTS: FilterProvider ------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns a list of all the filterNames in this FilterProvider.
     *
     * @return array
     */
    public function list()
    {
        try {
            $class = new \ReflectionClass($this);
            $methods = $class->getMethods(\ReflectionMethod::IS_PUBLIC);

            $list = [];

            foreach ($methods as $method) {
                $shortName = $method->getShortName();
                if(str_is('filter*', $shortName)) {
                    $list[] = snake_case(str_after($shortName, 'filter'));
                }
            }

            return $list;
        } catch (\ReflectionException $exception) {
            return [];
        }
    }

    /**
     * Returns the filtering Closure that belongs to the provided $filter and $params.
     *
     * @param string $filterName
     * @param mixed $params
     * @return \Closure
     * @throws FilterNotFoundException
     * @throws FilterInvalidParametersException
     */
    public function get($filterName, $params)
    {
        $methodName = $this->getMethodName($filterName);
        if(!method_exists($this, $methodName)) {
            throw new FilterNotFoundException;
        }

        $result = $this->$methodName($params);

        if(!($result instanceof \Closure)) {
            throw new \LogicException("The result of the filter-method was not a closure.");
        }

        return $result;
    }

    /**
     * Returns if this filter provider has a filter with the provided name.
     *
     * @param string $filterName
     * @return boolean
     */
    public function has($filterName)
    {
        if(method_exists($this, $this->getMethodName($filterName))) {
            return true;
        }
        return false;
    }

    /**
     * Returns if the provided parameters are allowed for the filter with the provided $filterName.
     *
     * @param string $filterName
     * @param mixed $params
     * @return boolean
     * @throws FilterNotFoundException
     */
    public function allows($filterName, $params)
    {
        try {
            $this->get($filterName, $params);
            return true;
        } catch (FilterInvalidParametersException $exception) {
            return false;
        }
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- HELPER METHODS ------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //


    /**
     * Returns the name of the method that belongs to provided $filterName.
     *
     * @param string $filterName
     * @return string
     */
    protected function getMethodName($filterName)
    {
        $camelCase = camel_case($filterName);
        $methodName = 'filter'.ucfirst($camelCase);
        return $methodName;
    }

    /**
     * Takes a string in a `key1:value1,key2:value2` format and turns it into an assoc-array.
     *
     * @param string $param
     * @return array
     * @throws FilterInvalidParametersException
     */
    protected function parseAssocParam($param)
    {
        $res = [];

        $pairs = explode(',', $param);
        foreach ($pairs as $pair) {
            $pieces = explode(':',$pair, 2);
            if(count($pieces) === 1) {
                $key = $pieces[0];
                $value = true;
            } else {
                $key = $pieces[0];
                $value = $pieces[1];
            }

            if(array_key_exists($res, $key)) {
                throw new FilterInvalidParametersException("Found the same key $key multiple times.");
            }

            $res[$key] = $value;
        }

        return $res;
    }

    /**
     * Parses a parameter that contains a date.
     *
     * @param string $param
     * @throws FilterInvalidParametersException
     * @return Carbon
     */
    protected function parseDateParam($param) {
        if(!is_string($param)) {
            throw new FilterInvalidParametersException("The parameter must be a string.");
        }

        try {
            $date = Carbon::parse($param);
        } catch (\Exception $exception) {
            throw new FilterInvalidParametersException("The parameter must be a valid date-string.", 0, $exception);
        }

        if(!($date instanceof Carbon)) {
            throw new FilterInvalidParametersException("The parameter must be a valid date-string.");
        }

        return $date;
    }

    /**
     * Parses a parameter that contains a reference to a model.
     *
     * @param mixed $param
     * @param string $modelName
     * @throws FilterInvalidParametersException
     * @return Model
     */
    protected function parseModelParam($param, $modelName) {
        $finder = $this->finderCollection;

        try {
            return $finder->find($param, $modelName);
        } catch (InputNotAcceptedException $e) {
            throw new FilterInvalidParametersException("Can't initiate a $modelName from the provided value.", 0, $e);
        } catch (ModelNotFoundException $e) {
            throw new FilterInvalidParametersException("Can't find a $modelName from the provided value.", 0, $e);
        }
    }

}