<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 25-06-18
 * Time: 01:03
 */

namespace Roelhem\RbacGraph\Database\Tools;


use Roelhem\RbacGraph\Contracts\Graphs\Graph;
use Roelhem\RbacGraph\Contracts\Tools\PathFinder;
use Roelhem\RbacGraph\Database\DatabaseGraph;
use Roelhem\RbacGraph\Database\Path;

class DatabasePathFinder implements PathFinder
{

    /**
     * Gives the graph where this object belongs to.
     *
     * @return Graph
     */
    public function getGraph()
    {
        return resolve(DatabaseGraph::class);
    }

    /** @inheritdoc */
    public function exists($start, $end)
    {
        return Path::between($start, $end)->exists();
    }

    /** @inheritdoc */
    public function count($start, $end)
    {
        return Path::between($start, $end)->count();
    }

    /** @inheritdoc */
    public function find($start, $end)
    {
        return Path::between($start, $end)->orderBy('size')->first();
    }

    /** @inheritdoc */
    public function findAll($start, $end)
    {
        return Path::between($start, $end)->orderBy('size')->get();
    }
}