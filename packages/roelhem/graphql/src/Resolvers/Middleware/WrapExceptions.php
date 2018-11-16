<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16/11/2018
 * Time: 19:09
 */

namespace Roelhem\GraphQL\Resolvers\Middleware;


use Roelhem\GraphQL\Exceptions\ValidationException;
use Roelhem\GraphQL\Resolvers\ResolveContext;
use Roelhem\GraphQL\Resolvers\ResolveStore;

class WrapExceptions
{

    protected $map = [];

    /**
     * WrapExceptions constructor.
     * @param array|string|null $map
     */
    public function __construct($map = null)
    {
        if(is_string($map)) {
            $map = [$map];
        } elseif(!is_array($map)) {
            $map = [
                ValidationException::class,
            ];
        }

        foreach ($map as $from => $to) {
            if(!class_exists($to)) {
                throw new \InvalidArgumentException("Can't find the class with className $to.");
            }

            if(!is_subclass_of($to, \Throwable::class)) {
                throw new \InvalidArgumentException("Can't throw the class $to.");
            }


            if(is_numeric($from)) {
                try {
                    $reflect = new \ReflectionClass($to);
                    $constructor = $reflect->getConstructor();
                    $firstParam = $constructor->getParameters()[0];
                    $from = $firstParam->getClass()->getName();
                } catch (\Throwable $exception) {
                    throw new \InvalidArgumentException("Can't find the wrapping Exception class for $to.",0,$exception);
                }
            }

            if(!class_exists($from)) {
                throw new \InvalidArgumentException("Can't find the class with className $from.");
            }

            if(!is_subclass_of($to, \Throwable::class)) {
                throw new \InvalidArgumentException("The class $from cannot be thrown.");
            }

            $this->map[$from] = $to;
        }
    }

    /**
     * @param $source
     * @param $args
     * @param ResolveContext $context
     * @param ResolveStore $store
     * @param \Closure $next
     * @return mixed
     * @throws
     */
    public function __invoke($source, $args, ResolveContext $context, ResolveStore $store, \Closure $next)
    {
        try {
            return $next($source, $args, $context, $store);
        } catch (\Throwable $exception) {
            foreach ($this->map as $from => $to) {
                if(is_a($exception, $from)) {
                    throw new $to($exception);
                }
            }
            throw $exception;
        }
    }

}