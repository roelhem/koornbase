<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 12-06-18
 * Time: 22:04
 */

namespace Roelhem\RbacGraph\Builders;


use Roelhem\RbacGraph\Contracts\Builder as BuilderContract;
use Roelhem\RbacGraph\Contracts\MutableNode;
use Roelhem\RbacGraph\Contracts\Node as NodeContract;
use Roelhem\RbacGraph\Contracts\NodeBuilder as NodeBuilderContract;
use Roelhem\RbacGraph\Exceptions\RbacGraphException;


class NodeBuilder implements NodeBuilderContract
{

    /**
     * The builder of this NodeBuilder.
     *
     * @var BuilderContract
     */
    protected $builder;

    /**
     * The node of this NodeBuilder.
     *
     * @var NodeContract
     */
    protected $node;

    /**
     * NodeBuilder constructor.
     * @param BuilderContract $builder
     * @param NodeContract $node
     */
    public function __construct(BuilderContract $builder, NodeContract $node)
    {
        $this->builder = $builder;
        $this->node = $node;
    }

    /**
     * @inheritdoc
     */
    public function getBuilder()
    {
        return $this->builder;
    }

    /**
     * @inheritdoc
     */
    public function getGraph()
    {
        return $this->getNode()->getGraph();
    }

    /**
     * @inheritdoc
     */
    public function getNode()
    {
        return $this->node;
    }

    /**
     * @inheritdoc
     */
    public function description($description)
    {
        $node = $this->getNode();
        if($node instanceof MutableNode) {
            $node->setDescription($description);
        } else {
            throw new RbacGraphException("The node in this builder is not mutable.");
        }
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function title($title)
    {
        $node = $this->getNode();
        if($node instanceof MutableNode) {
            $node->setTitle($title);
        } else {
            throw new RbacGraphException("The node in this builder is not mutable.");
        }
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function options($options)
    {
        $node = $this->getNode();
        if($node instanceof MutableNode) {
            foreach ($options as $key => $value) {
                $node->setOption($key, $value);
            }
        } else {
            throw new RbacGraphException("The node in this builder is not mutable.");
        }
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function assign(...$children)
    {
        collect($children)->flatten()->each(function($child) {
            $this->getBuilder()->edge($this, $child);
        });
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function assignTo(...$parents)
    {
        collect($parents)->flatten()->each(function($parent) {
            $this->getBuilder()->edge($parent, $this);
        });
        return $this;
    }

}