<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 00:32
 */

namespace Roelhem\GraphQL;


use GraphQL\Error\Debug;
use GraphQL\Server\StandardServer;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;
use Roelhem\GraphQL\Contracts\TypeLoaderContract;
use Roelhem\GraphQL\Resolvers\DefaultResolver;
use Roelhem\GraphQL\Resolvers\ResolveContext;

class GraphQL
{

    /** @var TypeLoaderContract */
    protected $typeLoader;

    /** @var Schema */
    protected $schema;

    /** @var ResolveContext */
    protected $context;

    /** @var StandardServer */
    protected $server;

    /**
     * GraphQL constructor.
     * @param TypeLoaderContract $typeLoader
     */
    public function __construct(TypeLoaderContract $typeLoader)
    {
        $this->typeLoader = $typeLoader;
    }

    /**
     * Returns the GraphQL schema.
     *
     * @return Schema
     */
    public function schema()
    {
        if($this->schema === null) {
            $this->schema = new Schema([
                'query' => $this->type('Query'),
                'mutation' => $this->type('Mutation'),
                'typeLoader' => $this->typeLoader,
                'types' => $this->types(),
            ]);
        }
        return $this->schema;
    }

    /**
     * Returns the context object.
     *
     * @return ResolveContext
     */
    public function context()
    {
        if($this->context === null) {
            $this->context = new ResolveContext(\Auth::guard('api'));
        }
        return $this->context;
    }

    /**
     * Returns the StandardServer instance.
     *
     * @return StandardServer
     */
    public function server()
    {
        if($this->server === null) {
            $this->server = new StandardServer([
                'schema' => $this->schema(),
                'context' => $this->context(),
                'queryBatching' => true,
                'debug' => Debug::INCLUDE_DEBUG_MESSAGE | Debug::INCLUDE_TRACE,
                'fieldResolver' => new DefaultResolver()
            ]);
        }
        return $this->server;
    }

    /**
     * Returns the instance of the typeLoader.
     *
     * @return TypeLoaderContract
     */
    public function typeLoader()
    {
        return $this->typeLoader;
    }

    /**
     * Returns the instance of the Type that belongs to the provided $type.
     *
     * @param string|Type $type
     * @return Type
     */
    public function type($type)
    {
        return $this->typeLoader()->load($type);
    }

    /**
     * Returns all the (base) types
     *
     * @return Type[]
     */
    public function types()
    {
        return $this->typeLoader()->repository()->getAll();
    }


}