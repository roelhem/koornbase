<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 04-06-18
 * Time: 06:26
 */

namespace App\Services\Finders;


trait UseModelClassAndNameProperties
{
    /**
     * @var string
     */
    protected $modelClass;

    /**
     * @var string
     */
    protected $modelName;

    /**
     * @inheritdoc
     */
    public function modelClass(): string
    {
        return $this->modelClass;
    }

    /**
     * @inheritdoc
     */
    public function modelName(): string
    {
        return $this->modelName;
    }
}