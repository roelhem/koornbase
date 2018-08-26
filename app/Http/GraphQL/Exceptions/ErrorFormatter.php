<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 26-08-18
 * Time: 20:27
 */

namespace App\Http\GraphQL\Exceptions;


use GraphQL\Error\Error;
use Rebing\GraphQL\Error\ValidationError;

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
        if(!empty($locations)) {
            $res['locations'] = $locations;
        }

        return array_merge($res, $this->getPrevious());
    }

    /**
     * Returns the locations of the error.
     *
     * @return array
     */
    protected function getLocations()
    {
        $locations = $this->error->getLocations();
        if(empty($locations)) {
            return [];
        }

        $res = [];
        foreach ($locations as $location) {
            $res[] = $location->toArray();
        }
        return $res;
    }

    protected function getPrevious()
    {
        $previous = $this->error->getPrevious();

        if($previous instanceof \Exception) {

            if($previous instanceof ValidationError) {
                return ['validation' => $previous->getValidatorMessages()];
            }

            return [
                'previous' => [
                    'message' => $previous->getMessage(),
                    'file' => $previous->getFile(),
                    'line' => $previous->getLine(),
                ]
            ];

        }

        return [];
    }

}