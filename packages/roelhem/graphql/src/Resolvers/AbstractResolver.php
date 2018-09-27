<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16-09-18
 * Time: 18:36
 */

namespace Roelhem\GraphQL\Resolvers;


use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Fluent;

abstract class AbstractResolver
{

    /**
     * A reference to the closure that needs to be invoked to start the resolver.
     *
     * @var \Closure
     */
    protected $callback;


    /**
     * AbstractResolver constructor.
     * @param array $middleware
     */
    public function __construct($middleware = [])
    {
        $this->callback = $this->createClosures($middleware);
    }




    /**
     * Method that creates the structure of \Closure functions that is needed to run all the middleware.
     *
     * @param array $middleware
     * @return \Closure
     */
    protected function createClosures($middleware) {

        // The last closure, that will call the handle function to generate the response.
        $closure = function($source, $args, ResolveContext $context, ResolveStore $store) {
            return $this->handle($source, $args, $context, $store);
        };

        // Create a new closure for every middleware function
        foreach (array_reverse($middleware) as $middlewareItem) {
            $callable = $this->getMiddlewareCallable($middlewareItem);
            $closure = function($source, $args, ResolveContext $context, ResolveStore $store) use ($callable, $closure) {
                return call_user_func($callable, $source, $args, $context, $store, $closure);
            };
        }

        // Return the last closure. This closure should be called when you want to run all the middleware
        // and the handler of this resolver.
        return $closure;
    }




    /**
     * Returns a callable object that represents the
     *
     * @param mixed $middleware
     * @return callable
     */
    protected function getMiddlewareCallable($middleware)
    {
        if(is_string($middleware)) {
            $middleware = resolve($middleware);
        }

        if(is_callable($middleware)) {
            return $middleware;
        }

        throw new \UnexpectedValueException("Unable to create a callable middleware from the input $middleware.");
    }





    /**
     * This method will be called if the resolver needs to resolve something.
     *
     * @param mixed $source
     * @param array $args
     * @param ResolveContext $context
     * @param ResolveInfo $resolveInfo
     * @return mixed
     */
    public function __invoke($source, $args, ResolveContext $context, ResolveInfo $resolveInfo)
    {
        return $this->start($source, new Fluent($args), $context, new ResolveStore($resolveInfo));
    }



    /**
     * Method that will be called to start the execution of the middlewares.
     *
     * @param mixed $source
     * @param Fluent $args
     * @param ResolveContext $context
     * @param ResolveStore $store
     * @return mixed
     */
    protected function start($source, $args, ResolveContext $context, ResolveStore $store)
    {
        return ($this->callback)($source, $args, $context, $store);
    }



    /**
     * This method will be called after all the middleware-function were run for the first time. Afterward,
     * all the response changes of the middleware will be processed.
     *
     * @param mixed $source
     * @param Fluent $args
     * @param ResolveContext $context
     * @param ResolveStore $store
     * @return mixed
     */
    abstract public function handle($source, $args, ResolveContext $context, ResolveStore $store);

}