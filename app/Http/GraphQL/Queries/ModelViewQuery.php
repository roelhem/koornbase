<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 00:13
 */

namespace App\Http\GraphQL\Queries;


use App\Http\GraphQL\Queries\Traits\HasModelClassName;
use GraphQL\Error\Error;
use GraphQL\Type\Definition\Type;
use Illuminate\Database\Eloquent\Builder;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class ModelViewQuery extends Query
{

    use HasModelClassName;

    public function __construct($modelClass = null, $attributes = [])
    {
        if($modelClass !== null) {
            $this->modelClass = $modelClass;
        }
        parent::__construct($attributes);
    }

    public function attributes()
    {

        $typeName = $this->getTypeName();

        return [
            'name' => camel_case($typeName),
            'description' => "Shows the `$typeName` that matches `ID` in the args."
        ];
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- ABSTRACT FUNCTIONS --------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Method that should return a query that returns the model to view.
     *
     * @param array $args
     * @param SelectFields $selectFields
     * @return Builder
     */
    protected function query($args, $selectFields) {
        return $this->getQuery();
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- CONFIGURATION METHODS ------------------------------------------------------------------------------ //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * A method that adds the necessary `where`-statements to the query to select the desired model.
     *
     * It can also be used to validate the parameters.
     *
     * @param Builder $query
     * @param $args
     * @return Builder
     * @throws Error
     */
    protected function filterQuery(Builder $query, $args)
    {
        // Try with ID
        if(array_has($args, 'id')) {
            return $this->selectWithId($query, $args['id']);
        }


        throw new Error("Can't find the {$this->getTypeName()}.");
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- QUERY CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /** @inheritdoc */
    public function authorize(array $args)
    {
        if(\Gate::allows('view', $this->modelClass)) {
            return true;
        };

        $model = $this->filterQuery($this->getQuery(), $args)->firstOrFail();
        return \Gate::allows('view',$model);
    }

    /** @inheritdoc */
    public function type()
    {
        return \GraphQL::type($this->getTypeName());
    }

    /** @inheritdoc */
    public function args()
    {
        return array_merge(
            $this->selectionArgs()
        );
    }

    /**
     * Returns the model to view.
     *
     * @param mixed $root
     * @param array $args
     * @param SelectFields $selectFields
     * @return \Illuminate\Database\Eloquent\Model|null|object
     * @throws Error
     */
    public function resolve($root, $args, $selectFields)
    {
        $query = $this->query($args, $selectFields);
        $query->with($selectFields->getRelations());
        $query->select($selectFields->getSelect());
        $query = $this->filterQuery($query, $args);
        return $query->first();
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- MODEL SELECTION ------------------------------------------------------------------------------------ //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * The arguments needed to find the model.
     *
     * @return array
     */
    protected function selectionArgs()
    {
        $res = [];

        $res['id'] = [
            'type' => Type::nonNull(Type::id()),
            'description' => 'The unique `ID`-identifier of the model to view.'
        ];

        return $res;
    }

    /**
     * A method that adds a `where`-statement to the provided query to find the right model using it's id.
     *
     * This method will also check if you provided a valid id.
     *
     * @param Builder $query
     * @param mixed $id
     * @return Builder
     * @throws Error
     */
    protected function selectWithId($query, $id)
    {
        if(is_string($id)) {
            if(!ctype_digit($id)) {
                throw new Error("If you provide an `ID` as a string, the string can only contain numbers.");
            }

            $id = intval($id);
        }

        if(!is_integer($id)) {
            throw new Error("The value of an `ID` has to be an integer or a string representing an integer.");
        }

        return $query->where('id','=', $id);
    }

}