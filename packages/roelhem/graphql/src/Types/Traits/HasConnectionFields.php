<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 27-09-18
 * Time: 05:17
 */

namespace Roelhem\GraphQL\Types\Traits;


use Roelhem\GraphQL\Fields\ConnectionField;
use Roelhem\GraphQL\Repositories\ConnectionTypeRepository;

trait HasConnectionFields
{

    /** @var ConnectionField[]|null */
    private $connectionFields;

    /** @var ConnectionTypeRepository|null */
    private $connectionTypeRepository;

    /**
     * Defines the connection-fields for this ConnectionType.
     *
     * @return array
     */
    protected function connections()
    {
        return [];
    }

    /**
     * Returns the connection-fields of this
     *
     * @return ConnectionField[]
     */
    public function getConnectionFields()
    {
        if($this->connectionFields === null) {
            $this->connectionFields = [];
            foreach ($this->connections() as $key => $value) {
                $field = $this->parseConnectionField($value, $key);
                $this->connectionFields[$field->name()] = $field;
            }
        }
        return $this->connectionFields;
    }

    /**
     * Returns a ConnectionTypeRepository with all the ConnectionTypes needed by this Type.
     *
     * @return ConnectionTypeRepository
     */
    public function getConnectionTypeRepository()
    {
        if($this->connectionTypeRepository === null) {
            $this->connectionTypeRepository = new ConnectionTypeRepository();
            $this->connectionTypeRepository->addFields($this->getConnectionFields());
        }
        return $this->connectionTypeRepository;
    }

    /**
     * Creates a new ConnectionField for this Type based on the provided input.
     *
     * @param array|ConnectionField|string $value
     * @param null $key
     * @return ConnectionField
     */
    protected function parseConnectionField($value, $key = null) {
        if($value instanceof ConnectionField) {
            return $value;
        }

        if(is_string($value)) {
            $value = ['toType' => $value];
        }

        if(!is_array($value)) {
            throw new \InvalidArgumentException("Can't parse this value to a ConnectionField. The value parameter has to be a string, array or instance of ".ConnectionField::class);
        }

        $config = array_merge([
            'name' => is_string($key) ? $key : null,
            'fromType' => $this,
        ], $value);
        
        return new ConnectionField($config);
    }
}