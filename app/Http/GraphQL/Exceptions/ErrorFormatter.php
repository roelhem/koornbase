<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 26-08-18
 * Time: 20:27
 */

namespace App\Http\GraphQL\Exceptions;


use GraphQL\Error\Error;

class ErrorFormatter
{

    public static function format(Error $e)
    {
        $formatter = new static($e);

        return $formatter->asArray();
    }

    /**
     * @var Error
     */
    protected $error;

    /**
     * ErrorFormatter constructor.
     *
     * @param $error
     */
    public function __construct(Error $error)
    {
        $this->error = $error;
    }

    /**
     * Returns the error as an formatted array.
     *
     * @return array
     */
    public function asArray()
    {
        $res = [
            'message' => $this->error->getMessage(),
        ];

        $locations = $this->getLocations();
        if(sizeof($locations)) {
            $res['locations'] = $locations;
        }

        return $res;
    }

    /**
     * Returns the locations of the error.
     *
     * @return array
     */
    protected function getLocations()
    {
        $res = [];
        foreach ($this->error->getLocations() as $location) {
            $res[] = $location->toArray();
        }
        return $res;
    }

}