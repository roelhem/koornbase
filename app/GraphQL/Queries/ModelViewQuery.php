<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-07-18
 * Time: 00:13
 */

namespace App\GraphQL\Queries;


use GraphQL\Error\Error;
use GraphQL\Type\Definition\Type;
use Illuminate\Database\Eloquent\Builder;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

abstract class ModelViewQuery extends Query
{

    /**
     * The name of the type of this ModelViewQuery.
     *
     * @var string $typeName
     */
    protected $typeName;


    /**
     * Specifies if this query should also allow the selecting of models by there slug. If it has a string-value,
     * this string will be used as the column name of the slug column.
     *
     * @var bool|string $slug
     */
    protected $slug = false;

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
    protected abstract function query($args, $selectFields);

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

        // Try with Slug
        if(array_has($args, 'slug')) {
            return $this->selectWithSlug($query, $args['slug']);
        }



        throw new Error("Can't find the {$this->typeName} based on the provided arguments.");
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- QUERY CONFIGURATION -------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /** @inheritdoc */
    public function type()
    {
        if($this->typeName === null) {
            throw new \LogicException("Can't find a \$typeName for this ModelViewQuery. ");
        }

        return \GraphQL::type($this->typeName);
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
            'type' => Type::id(),
            'description' => 'The unique `ID`-identifier of the model to view.'
        ];

        if($this->useSlug()) {
            $res['slug'] = [
                'type' => Type::string(),
                'description' => 'Selects the model based on it\'s slug. An slug is an URL-safe string that uniquely identifies a model of a certain type.'
            ];
        }

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

    /**
     * A method that adds a `where`-statement to the provided query to find the right model using it's slug.
     *
     * @param Builder $query
     * @param string $slug
     * @return Builder
     * @throws Error
     */
    protected function selectWithSlug($query, $slug)
    {
        if(!$this->useSlug()) {
            throw new Error("You can't select a {$this->typeName} using a slug.");
        }

        if(!is_string($slug)) {
            throw new Error('A slug has to be a valid string.');
        }

        return $query->where($this->getSlugColumn(), '=', $slug);
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- HELPER METHODS ------------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Returns if this ModelViewQuery should allow to select models with slugs.
     *
     * @return bool
     */
    protected function useSlug()
    {
        return is_string($this->slug) || $this->slug === true;
    }

    /**
     * Returns the name of the column that contains the slug value.
     *
     * @return string
     */
    protected function getSlugColumn()
    {
        if(is_string($this->slug)) {
            return $this->slug;
        } else {
            return 'slug';
        }
    }

}