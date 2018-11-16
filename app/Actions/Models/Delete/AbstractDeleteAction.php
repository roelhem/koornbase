<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16/11/2018
 * Time: 21:33
 */

namespace App\Actions\Models\Delete;


use App\Actions\Models\AbstractModelAction;
use Illuminate\Database\Eloquent\Model;
use Roelhem\Actions\Contracts\ActionContextContract;
use Roelhem\GraphQL\Facades\GraphQL;

abstract class AbstractDeleteAction extends AbstractModelAction
{

    /**
     * Default create action, by calling the delete-method on the model instance.
     *
     * @param array $validArgs
     * @param null|ActionContextContract $context
     * @return Model
     * @throws
     */
    protected function handle($validArgs = [], ?ActionContextContract $context = null)
    {
        $id = array_get($validArgs,'id');
        /** @var Model $model */
        $model = call_user_func([$this->getModelClass(), 'findOrFail'], $id);

        // Delete the model
        $model->delete();

        // Return the model that was just deleted.
        return $model;
    }

    /** @inheritdoc */
    public function args()
    {
        return [
            'id' => [
                'description' => 'The `ID` of the `'.$this->type()->name.'` that should be deleted.',
                'type' => GraphQL::type('ID!'),
            ]
        ];
    }

    /** @inheritdoc */
    public function description() {
        $description = parent::description();
        if($description === null) {
            $description = 'Deletes the `'.$this->type()->name.'` with the specified `ID` from the database.';
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
            if(starts_with($name, 'Delete')) {
                $name = str_after($name,'Delete');
            }
            $this->type = $name;
        }
        return parent::type();
    }



}