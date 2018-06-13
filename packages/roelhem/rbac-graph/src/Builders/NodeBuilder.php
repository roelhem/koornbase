<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 12-06-18
 * Time: 22:04
 */

namespace Roelhem\RbacGraph\Builders;


use Roelhem\RbacGraph\Contracts\Builder as BuilderContract;
use Roelhem\RbacGraph\Contracts\NodeBuilder as NodeBuilderContract;
use Roelhem\RbacGraph\Enums\NodeType;
use Roelhem\RbacGraph\Exceptions\EdgeNotAllowedException;
use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;
use Roelhem\RbacGraph\Exceptions\NodeTypeNotFoundException;
use Roelhem\RbacGraph\Nodes\Traits\HasNodeProperties;



class NodeBuilder implements NodeBuilderContract
{

    use HasNodeProperties;

    /**
     * NodeBuilder constructor.
     * @param BuilderContract $builder
     * @param int|NodeType $type
     * @param string $name
     * @param int $id
     */
    public function __construct(BuilderContract $builder, $type, string $name, int $id)
    {
        $this->graph = $builder;

        $this->type = NodeType::get($type);
        $this->name = $name;
        $this->id = $id;
    }

    /**
     * @inheritdoc
     */
    public function description($description)
    {
        $this->setDescription($description);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function title($title)
    {
        $this->setTitle($title);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function assign(...$children)
    {
        collect($children)->flatten()->each([$this, 'assignOne']);
        return $this;
    }

    /**
     * Helper method for the assign method.
     *
     * @param $child
     * @throws NodeNotFoundException
     * @throws EdgeNotAllowedException
     */
    public function assignOne($child) {
        $builder = $this->getBuilder();
        if($builder->hasNode($child)) {
            $builder->edge($this, $child);
        }
    }

    /**
     * @inheritdoc
     */
    public function assignTo(...$parents)
    {
        collect($parents)->flatten()->each([$this, 'assignToOne']);
        return $this;
    }

    /**
     * @param $parent
     * @throws NodeNotFoundException
     * @throws EdgeNotAllowedException
     */
    public function assignToOne($parent) {
        $builder = $this->getBuilder();
        if ($builder->hasNode($parent)) {
            $builder->edge($parent, $this);
        }
    }

    /**
     * @return BuilderContract
     */
    public function getBuilder()
    {
        return $this->graph;
    }

}