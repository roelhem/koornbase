<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 26-08-18
 * Time: 15:41
 */

namespace App\GraphQL\Mutations\Crud\Delete;


use GraphQL\Type\Definition\Type;
use Illuminate\Database\Eloquent\Model;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\Type as GraphQLType;

/**
 * Class AbstractDeleteMutation
 *
 * Contains some basic functionality for delete mutations.
 *
 * When extending this class, choose the name of the class such that the short name of the model is between the
 * words `Delete` and `Mutation`. In this way, the methods of this class can guess most of the options.
 *
 * @package App\GraphQL\Mutations\Crud\Delete
 */
abstract class AbstractDeleteMutation extends Mutation
{

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- DEFAULT FUNCTIONS ---------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * The attributes of this mutation.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'name' => $this->getName(),
            'description' => "Deletes a specific `".$this->getTypeName()."` with the specified `ID`.",
        ];
    }

    /**
     * @return GraphQLType
     */
    public function type()
    {
        return \GraphQL::type($this->getTypeName());
    }

    /**
     * The arguments for the mutations.
     *
     * @return array
     */
    public function args()
    {
        return [
            'id' => [
                'description' => 'The `ID` of the `'.$this->getTypeName().'` that should be deleted.',
                'type' => Type::nonNull(Type::id())
            ]
        ];
    }

    /**
     * Handles the deleting of the model.
     *
     * @param mixed $root
     * @param array $args
     * @throws \Exception
     * @return Model
     */
    public function resolve($root, $args)
    {
        // Try to find the model instance.
        $id = array_get($args, 'id');
        /** @var Model $model */
        $model = call_user_func([$this->getModelClass(), 'findOrFail'], $id);

        // Delete the model
        $model->delete();

        // Return the deleted model.
        return $model;
    }


    // ---------------------------------------------------------------------------------------------------------- //
    // ----- OPTIONS -------------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * @var string|null $typeName The mutation name.
     */
    protected $typeName = null;

    /**
     * @var string|null $modelClass The class name of the controlled model.
     */
    protected $modelClass = null;

    /**
     * @var string|null $name The name of the mutation.
     */
    protected $name = null;

    /**
     * @var string|null $table The name of the table that stores the modes that are controlled by this mutation.
     */
    protected $tableName = null;

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- OPTION GETTERS AND GUESSERS ------------------------------------------------------------------------ //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns the name of the mutation-type.
     *
     * @return string
     */
    protected function getTypeName()
    {
        if($this->typeName === null) {
            $this->typeName = $this->getDefaultTypeName();
        }
        return $this->typeName;
    }

    /**
     * Returns the ModelClass that is controlled by this mutation. If no $modelClass property was given, the typeName
     * option will be used to guess the model class.
     *
     * @return string
     */
    protected function getModelClass()
    {
        if($this->modelClass === null) {
            $this->modelClass = 'App\\'.$this->getTypeName();
        }
        return $this->modelClass;
    }

    /**
     * Returns the name of the mutation.
     *
     * @return string
     */
    protected function getName()
    {
        if($this->name === null) {
            $this->name = 'delete'.$this->getTypeName();
        }
        return $this->name;
    }

    /**
     * Returns the name of the table that stores the models.
     *
     * @return string
     */
    protected function getTableName()
    {
        if($this->tableName === null) {
            $modelClass = $this->getModelClass();
            /** @var Model $model */
            $model = new $modelClass();
            $this->tableName = $model->getTable();
        }
        return $this->tableName;
    }

    /**
     * Returns the default typeName for the mutation type based on the class name.
     *
     * @return string
     */
    protected function getDefaultTypeName()
    {
        try {
            $reflection = new \ReflectionClass($this);
            $shortName = $reflection->getShortName();

            // Remove the 'Delete' prefix
            if(starts_with($shortName, 'Delete')) {
                $shortName = str_after($shortName, 'Delete');
            }

            // Remove the 'Mutation' suffix
            if(ends_with($shortName,'Mutation')) {
                $shortName = str_before($shortName, 'Mutation');
            }

            return $shortName;
        } catch (\ReflectionException $reflectionException) {
            return get_class($this);
        }

    }

}