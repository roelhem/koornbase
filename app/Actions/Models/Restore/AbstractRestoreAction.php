<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 17/11/2018
 * Time: 01:23
 */

namespace App\Actions\Models\Restore;


use App\Actions\Models\AbstractModelAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Roelhem\Actions\Contracts\ActionContext;
use Roelhem\GraphQL\Facades\GraphQL;

abstract class AbstractRestoreAction extends AbstractModelAction
{

    /**
     * Default restore handler. Works by finding the right instance and calling the restore method on this instance.
     *
     * @param array $validArgs
     * @param null|ActionContext $context
     * @return Model|mixed
     * @throws
     */
    protected function handle($validArgs = [], ?ActionContext $context = null)
    {
        $id = array_get($validArgs,'id');
        /** @var Builder $query */
        $query = call_user_func([$this->getModelClass(), 'withTrashed']);
        /** @var Model|SoftDeletes $model */
        $model = $query->findOrFail($id);


        // Check if the model is indeed trashed
        if(!$model->trashed()) {
            throw new \Exception('The `'.$this->type()->name.'` you want to restore, is not deleted.');
        }

        // Restore the model
        $model->restore();

        // Return the restored model
        return $model;
    }

    /** @inheritdoc */
    public function args()
    {
        return [
            'id' => [
                'type' => GraphQL::type('ID'),
                'description' => 'The `ID` of the (soft-deleted) `'.$this->type()->name.'` that you want to restore.'
            ],
        ];
    }

    /** @inheritdoc */
    public function description() {
        $description = parent::description();
        if($description === null) {
            $description = 'Restores a (soft-deleted) `'.$this->type()->name.'`. This makes the Model visible again in the normal database.';
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
            if(starts_with($name, 'Restore')) {
                $name = str_after($name,'Restore');
            }
            $this->type = $name;
        }
        return parent::type();
    }
}