<?php

namespace Roelhem\RbacGraph\Graphs\Traits;

use Roelhem\RbacGraph\Contracts\Nodes\Node;
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
            if($this->contains($node)) {
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