<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 14-06-18
 * Time: 01:27
 */

namespace Roelhem\RbacGraph\Services;


use Roelhem\RbacGraph\Contracts\Services\Builder;
use Roelhem\RbacGraph\Contracts\Graphs\Graph;

/**
 * Class DefaultRbacService
 *
 * RbacService that uses the settings in the service-provider.
 *
 * @package Roelhem\RbacGraph\Services
 */
class DefaultRbacService extends AbstractService
{

    /**
     * @var Graph
     */
    protected $graph;

    /**
     * @var Builder
     */
    protected $builder;

    /**
     * DefaultRbacService constructor.
     * @param Graph $graph
     * @param Builder $builder
     */
    public function __construct(Graph $graph, Builder $builder)
    {
        $this->graph = $graph;
        $this->builder = $builder;
    }

    /**
     * @inheritdoc
     */
    public function graph()
    {
        return $this->graph;
    }

    /**
     * @inheritdoc
     */
    public function builder()
    {
        return $this->builder;
    }

}