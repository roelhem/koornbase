<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16/11/2018
 * Time: 21:54
 */

namespace App\Actions\Models\Update;


use App\Actions\Models\AbstractModelAction;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Roelhem\Actions\Contracts\ActionContext;

abstract class AbstractUpdateAction extends AbstractModelAction
{
    /** @inheritdoc */
    public function description()
    {
        $description = parent::description();
        if($description === null) {
            $description = 'Updates the values of one `'.$this->type()->name.'` in the database.';
        }
        return $description;
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
            if(starts_with($name, 'Update')) {
                $name = str_after($name,'Update');
            }
            $this->type = $name;
        }
        return parent::type();
    }

    /**
     * Default update action, by calling the update-method on the model instance.
     *
     * @param array $validArgs
     * @param null|ActionContext $context
     * @return Model
     * @throws
     */
    protected function handle($validArgs = [], ?ActionContext $context = null)
    {
        $id = array_get($validArgs,'id');
        /** @var Model $model */
        $model = call_user_func([$this->getModelClass(), 'findOrFail'], $id);

        // Check if the update ability for this model is allowed in this context.
        if(!$context->can('update', $model)) {
            throw new AuthorizationException("Not allowed to update this model.");
        };

        // Fill the values of the model
        $model->fill(array_except($validArgs,'id'));

        // Save the changes
        $model->saveOrFail();

        // return the updated model as the result of this action.
        return $model;
    }
}