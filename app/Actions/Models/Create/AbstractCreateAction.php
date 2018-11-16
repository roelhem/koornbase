<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16/11/2018
 * Time: 20:57
 */

namespace App\Actions\Models\Create;


use App\Actions\Models\AbstractModelAction;
use Illuminate\Database\Eloquent\Model;
use Roelhem\Actions\Contracts\ActionContextContract;

abstract class AbstractCreateAction extends AbstractModelAction
{

    /**
     * Default create action, by calling the create-method on the modelClass, with the validated arguments as parameter.
     *
     * @param array $validArgs
     * @param null|ActionContextContract $context
     * @return Model
     */
    protected function handle($validArgs = [], ?ActionContextContract $context = null)
    {
        return call_user_func([$this->getModelClass(), 'create'],$validArgs);
    }

    /** @inheritdoc */
    public function type()
    {
        if($this->type === null) {
            $reflection = new \ReflectionClass($this);
            $name = $reflection->getShortName();
            if(ends_with($name,'Action')) {
                $name = str_before($name, 'Action');
            }
            if(starts_with($name, 'Create')) {
                $name = str_after($name,'Create');
            }
            $this->type = $name;
        }
        return parent::type();
    }

    /** @inheritdoc */
    public function description()
    {
        $description = parent::description();
        if($description === null) {
            $description = 'Adds a new `'.$this->type()->name.'` to the database.';
        }
        return $description;
    }

}