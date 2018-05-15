<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 14-05-18
 * Time: 00:03
 */

namespace App\Exceptions;


use App\Types\OptionsType;
use Throwable;
use Exception;

class OptionNotFoundException extends Exception
{

    public $optionsType;
    public $optionKey;

    /**
     * OptionNotFoundException constructor.
     *
     * @param OptionsType $optionsType
     * @param string|null $optionKey
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(OptionsType $optionsType, $optionKey = null, int $code = 0, Throwable $previous = null)
    {
        $this->optionsType = $optionsType;
        $this->optionKey = $optionKey;

        parent::__construct($this->constructMessage(), $code, $previous);
    }

    private function constructMessage() {
        if($this->optionKey === null) {
            return "Tried to access an option key that does not exist in this OptionsType.";
        } else {
            return "Tried to access the option `{$this->optionKey}` which does not exist in this OptionsType.";
        }
    }

}