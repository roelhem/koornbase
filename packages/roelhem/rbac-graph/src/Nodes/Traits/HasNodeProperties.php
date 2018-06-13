<?php

namespace Roelhem\RbacGraph\Nodes\Traits;

use Roelhem\RbacGraph\Contracts\Graph;
use Roelhem\RbacGraph\Enums\NodeType;

trait HasNodeProperties
{

    /**
     * @var Graph
     */
    protected $graph;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var NodeType
     */
    protected $type;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $title;

    /**
     * @var string|null
     */
    protected $description;

    /**
     * @return Graph
     */
    public function getGraph()
    {
        return $this->graph;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return NodeType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return null|string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     */
    public function setTitle($title)
    {
        if($title !== null) {
            $this->title = strval($title);
        } else {
            $this->title = null;
        }
    }

    /**
     * @return null|string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription($description)
    {
        if($description !== null) {
            $this->description = strval($description);
        } else {
            $this->description = null;
        }
    }

}