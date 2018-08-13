<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 12-06-18
 * Time: 23:44
 */

namespace Roelhem\RbacGraph\Graphs\Traits;


use Roelhem\RbacGraph\Contracts\Edges\Edge;

trait GraphRelatedFromEdgeMethods
{
    /**
     * @inheritdoc
     */
    public function getChildren($node)
    {
        return $this->getOutgoingEdges($node)->map(function(Edge $edge) {
            return $edge->getChild();
        });
    }

    /**
     * @inheritdoc
     */
    public function getParents($node)
    {
        return $this->getIncomingEdges($node)->map(function (Edge $edge) {
            return $edge->getParent();
        });
    }
}