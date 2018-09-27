<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 27-09-18
 * Time: 02:16
 */

namespace Roelhem\GraphQL\Fields;


use GraphQL\Type\Definition\FieldDefinition;
use Roelhem\GraphQL\Resolvers\AbstractResolver;

abstract class Field
{

    /** @var array */
    protected $config;

    /**
     * Field constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * Returns an array that can be used as the config parameter of a new FieldDefinition.
     *
     * @return array
     */
    public function getConfig()
    {
        return [
            'name' => $this->name(),
            'type' => $this->type(),
            'description' => $this->description(),

            'resolve' => $this->resolver(),
            'map' => array_get($this->config, 'map'),

            'args' => $this->args(),

            'deprecationReason' => array_get($this->config, 'deprecationReason'),
            'astNode' => array_get($this->config,'astNode'),
            'complexity' => array_get($this->config, 'complexity')
        ];
    }

    /**
     * Returns a new FieldDefinition
     *
     * @return FieldDefinition
     */
    public function create()
    {
        return FieldDefinition::create($this->getConfig());
    }

    /**
     * Returns the name of this field.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name();
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- OVERWRITE METHODS ---------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * The arguments of the field.
     *
     * @return array
     */
    protected function args()
    {
        return array_get($this->config,'args');
    }

    /**
     * The name of this field.
     *
     * @return string
     */
    protected function name()
    {
        return array_get($this->config,'name');
    }

    /**
     * The description of this field.
     *
     * @return string
     */
    protected function description()
    {
        return array_get($this->config, 'description');
    }

    /**
     * The type of this field.
     *
     * @return string
     */
    protected function type()
    {
        return array_get($this->config, 'type');
    }

    /**
     * Returns the resolver that should resolve this field.
     *
     * @return AbstractResolver|callable
     */
    protected function resolver()
    {
        return array_get($this->config,'resolve');
    }

}