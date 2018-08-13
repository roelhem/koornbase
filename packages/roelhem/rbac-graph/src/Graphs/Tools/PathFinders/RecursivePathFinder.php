<?php

namespace Roelhem\RbacGraph\Graphs\Tools\PathFinders;


use Roelhem\RbacGraph\Contracts\Graphs\Graph;
use Roelhem\RbacGraph\Contracts\Graphs\Path;
use Roelhem\RbacGraph\Contracts\Tools\PathFinder;
use Roelhem\RbacGraph\Graphs\Paths\ConcatPath;
use Roelhem\RbacGraph\Graphs\Paths\SingletonPath;
use Roelhem\RbacGraph\Traits\HasGraphProperty;

class RecursivePathFinder implements PathFinder
{

    use HasGraphProperty;

    /**
     * RecursivePathFinder constructor.
     * @param Graph $graph
     */
    public function __construct(Graph $graph)
    {
        $this->initGraph($graph);
    }

    /** @inheritdoc */
    public function exists($start, $end)
    {
        if ($this->getGraph()->nodeEquals($start, $end)) {
            return true;
        } else {
            foreach ($this->getGraph()->getChildren($start) as $child) {
                if($this->exists($child, $end)) {
                    return true;
                }
            }
            return false;
        }
    }

    /** @inheritdoc */
    public function count($start, $end)
    {
        if($this->getGraph()->nodeEquals($start, $end)) {
            return 1;
        } else {
            $res = 0;
            foreach ($this->getGraph()->getChildren($start) as $child) {
                $res += $this->count($child, $end);
            }
            return $res;
        }
    }

    /** @inheritdoc */
    public function find($start, $end)
    {
        if($this->getGraph()->nodeEquals($start, $end)) {
            return new SingletonPath($this->getGraph(), $end);
        } else {
            foreach ($this->getGraph()->getChildren($start) as $child) {
                $path = $this->find($child, $end);
                if($path instanceof Path) {
                    return new ConcatPath(new SingletonPath($this->getGraph(), $start), $path);
                }
            }
            return null;
        }
    }

    /** @inheritdoc */
    public function findAll($start, $end)
    {
        if($this->getGraph()->nodeEquals($start, $end)) {
            return [new SingletonPath($this->getGraph(), $end)];
        } else {
            $res = [];
            foreach ($this->getGraph()->getChildren($start) as $child) {
                foreach($this->findAll($child, $end) as $path) {
                    $res[] = new ConcatPath(new SingletonPath($this->getGraph(), $start), $path);
                }
            }
            return $res;
        }
    }

}