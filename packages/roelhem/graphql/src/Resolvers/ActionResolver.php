<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 16/11/2018
 * Time: 15:34
 */

namespace Roelhem\GraphQL\Resolvers;


use Illuminate\Support\Fluent;
use Roelhem\Actions\Contracts\ActionContract;
use Roelhem\GraphQL\Exceptions\ValidationException;
use Roelhem\GraphQL\Resolvers\Middleware\WrapExceptions;

class ActionResolver extends AbstractResolver
{

    /** @var ActionContract */
    protected $action;

    public function __construct(ActionContract $action, array $middleware = [])
    {
        $this->action = $action;

        parent::__construct(array_merge([
            WrapExceptions::class
        ],$middleware));
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
    public function handle($source, $args, ResolveContext $context, ResolveStore $store)
    {
        return $this->action->run($args->toArray(), $context);
    }
}