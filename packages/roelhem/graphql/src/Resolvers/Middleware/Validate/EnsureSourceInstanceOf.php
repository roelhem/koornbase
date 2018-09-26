<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 21-09-18
 * Time: 07:01
 */

namespace Roelhem\GraphQL\Resolvers\Middleware\Validate;


use App\Http\GraphQL\Interfaces\PaginationInterface;
use GraphQL\Error\Error;
use GraphQL\Utils\Utils;
use Illuminate\Support\Fluent;
use Roelhem\GraphQL\Resolvers\ResolveStore;

class EnsureSourceInstanceOf
{

    protected $className;

    public function __construct($className)
    {
        $this->className = $className;
    }

    /**
     * @param mixed $source
     * @param Fluent $args
     * @param $context
     * @param ResolveStore $store
     * @param \Closure $next
     * @return mixed
     * @throws Error
     */
    public function __invoke($source, $args, $context, ResolveStore $store, \Closure $next)
    {

        if(!is_a($source, $this->className)) {
            throw new Error(
                "The source of this `".$store->parentType."` has to be an instance of ".$this->className.". Now: ".Utils::printSafe($source),
                $store->fieldNodes
            );
        }

        return $next($source, $args, $context, $store);

    }

}