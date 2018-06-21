<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 21-06-18
 * Time: 07:04
 */

namespace Roelhem\RbacGraph\Helpers;


use Roelhem\RbacGraph\Contracts\BelongsToGraph;
use Roelhem\RbacGraph\Contracts\Graph;
use Roelhem\RbacGraph\Contracts\Node;
use Roelhem\RbacGraph\Traits\HasGraphProperty;

class IdGenerator implements BelongsToGraph
{

    use HasGraphProperty;

    /**
     * The value that is added to each id to generate the next id.
     *
     * @var int
     */
    protected $increments = 1;

    /**
     * The offset value when generating the next id.
     *
     * @var int
     */
    protected $offset = 0;


    /**
     * IdGenerator constructor.
     * @param Graph $graph
     * @param int|null $offset
     * @param int $increment
     */
    public function __construct(Graph $graph, ?int $offset = null, int $increment = 1)
    {
        $this->initGraph($graph);
        if($increment <= 0) {
            throw new \InvalidArgumentException("The increments value must be greater than 0.");
        }
        $this->increments = $increment;
        $this->setOffset($offset);
    }

    /**
     * Sets the offset to the provided value.
     *
     * @param int|null $offset
     */
    public function setOffset(?int $offset = null) {
        if($offset === null) {
            $maxId = $this->getGraph()->getNodes()->max(function(Node $node) {
                return $node->getId();
            });

            $this->offset = intval($maxId);
        } else {
            $this->offset = $offset;
        }
    }

    /**
     * Returns the last generated offset.
     *
     * @return int
     */
    public function last() {
        return $this->offset;
    }

    /**
     * Generates a new id.
     *
     * @return int
     */
    public function next() {
        $this->offset += $this->increments;
        if($this->getGraph()->hasNodeId($this->offset)) {
            return $this->next();
        } else {
            return $this->offset;
        }
    }

}