<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 25-06-18
 * Time: 05:47
 */

namespace Roelhem\RbacGraph\Database\Traits\Path;


use Illuminate\Database\Eloquent\Builder;
use Roelhem\RbacGraph\Graphs\Paths\Traits\HasEdgesFromNodeAt;
use Roelhem\RbacGraph\Graphs\Paths\Traits\HasNodeIdList;
use Roelhem\RbacGraph\Graphs\SubGraphs\Traits\HasNoAssignments;
use Roelhem\RbacGraph\Graphs\SubGraphs\Traits\SubGraphDefaultContains;
use Roelhem\RbacGraph\Database\Node;

trait PathContractImplementation
{

    use HasNoAssignments;
    use HasEdgesFromNodeAt;
    use HasNodeIdList;
    use SubGraphDefaultContains;

    /**
     * Returns a new query with all the nodes in this Path.
     *
     * @return Builder
     */
    public function nodes() {
        return Node::query()->whereIn('id',$this->path);
    }

    /** @inheritdoc */
    protected function getNodeIdList()
    {
        return $this->path;
    }

    /** @inheritdoc */
    public function count()
    {
        return $this->size;
    }

    /** @inheritdoc */
    public function getNodes()
    {
        return $this->nodes()->get();
    }

    /** @inheritdoc */
    public function hasNodeName($name)
    {
        return $this->nodes()->where('name','=', strval($name))->exists();
    }

    /** @inheritdoc */
    public function getFirstNode()
    {
        return $this->firstNode;
    }

    /** @inheritdoc */
    public function getLastNode()
    {
        return $this->lastNode;
    }
}