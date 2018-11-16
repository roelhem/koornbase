<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16/11/2018
 * Time: 20:54
 */

namespace App\Actions\Models;


use App\Actions\AbstractAction;
use Illuminate\Database\Eloquent\Model;
use Roelhem\GraphQL\Types\ModelType;

abstract class AbstractModelAction extends AbstractAction
{

    /** @var string */
    protected $modelClass;

    /**
     * @return string
     */
    public function getModelClass() {
        if($this->modelClass === null) {
            $type = $this->type();
            if($type instanceof ModelType) {
                $this->modelClass = $type->getModelClass();
            }
        }

        if(!is_subclass_of($this->modelClass,Model::class)) {
            throw new \InvalidArgumentException("Can't find the ModelClass for the createAction ".get_class($this));
        }

        return $this->modelClass;
    }

}