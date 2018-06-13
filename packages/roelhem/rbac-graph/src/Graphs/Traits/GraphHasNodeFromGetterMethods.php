<?php

namespace Roelhem\RbacGraph\Contracts\Traits;

use Roelhem\RbacGraph\Contracts\Node;
use Roelhem\RbacGraph\Contracts\Edge;
use Roelhem\RbacGraph\Exceptions\EdgeNotFoundException;
use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;

trait GraphHasNodeFromGetterMethods
{

    /**
     * @inheritdoc
     */
    public function hasNodeName($name)
    {
        try {
            $node = $this->getNodeByName($name);
            return ($node instanceof Node);
        } catch (NodeNotFoundException $exception) {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function hasNodeId($id)
    {
        try {
            $node = $this->getNodeById($id);
            return ($node instanceof Node);
        } catch (NodeNotFoundException $exception) {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function hasNode($node)
    {
        if (is_string($node)) {
            return $this->hasNodeName($node);
        }

        if (is_integer($node)) {
            return $this->hasNodeId($node);
        }

        if ($node instanceof Node) {
            if($this->equals($node->getGraph())) {
                return true;
            } else {
                try {
                    $node = $this->getNode($node);
                    return ($node instanceof Node);
                } catch (NodeNotFoundException $exception) {
                    return false;
                }
            }
        }

        return false;
    }

}