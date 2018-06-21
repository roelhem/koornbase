<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 12-06-18
 * Time: 23:03
 */

namespace Roelhem\RbacGraph\Graphs;


use Roelhem\RbacGraph\Builders\RbacBuilder;
use Roelhem\RbacGraph\Contracts\Builder;
use Roelhem\RbacGraph\Contracts\Edge;
use Roelhem\RbacGraph\Contracts\MutableGraph;
use Roelhem\RbacGraph\Contracts\Node;
use Roelhem\RbacGraph\Contracts\Traits\GraphDefaultContains;
use Roelhem\RbacGraph\Contracts\Traits\GraphDefaultEquals;
use Roelhem\RbacGraph\Contracts\Traits\HasAssignmentArray;
use Roelhem\RbacGraph\Contracts\Traits\HasEdgeDictionaries;
use Roelhem\RbacGraph\Contracts\Traits\HasNodeDictionaries;
use Roelhem\RbacGraph\Edges\SimpleEdge;
use Roelhem\RbacGraph\Enums\NodeType;
use Roelhem\RbacGraph\Helpers\IdGenerator;
use Roelhem\RbacGraph\Nodes\SimpleNode;

class DictionaryGraph implements MutableGraph
{

    use GraphDefaultContains;
    use GraphDefaultEquals;
    use HasNodeDictionaries;
    use HasEdgeDictionaries;
    use HasAssignmentArray;

    protected $idGen;

    public function __construct()
    {
        $this->idGen = new IdGenerator($this);
    }

    /**
     * @inheritdoc
     */
    public function builder()
    {
        return new RbacBuilder($this);
    }

    // ------------------------------------------------------------------------------------------------------------ //
    // ---------  NODES  ------------------------------------------------------------------------------------------ //
    // ------------------------------------------------------------------------------------------------------------ //

    /**
     * @inheritdoc
     */
    public function createNode($type, $name, $options = [])
    {
        $node = new SimpleNode($this,NodeType::by($type),$this->idGen->next(), $name, $options);
        $this->storeNode($node);
        return $node;
    }

    /**
     * @inheritdoc
     */
    public function addNode(Node $node)
    {
        $node = $this->createNode($node->getType(), $node->getName(), $node->getOptions());
        $node->setTitle($node->getTitle());
        $node->setDescription($node->getDescription());

        return $node;
    }

    // ------------------------------------------------------------------------------------------------------------ //
    // ---------  EDGES  ------------------------------------------------------------------------------------------ //
    // ------------------------------------------------------------------------------------------------------------ //

    /**
     * @inheritdoc
     */
    public function createEdge($parent, $child)
    {
        $edge = new SimpleEdge($this, $parent, $child);
        $this->storeEdge($edge);
        return $edge;
    }

    /**
     * @inheritdoc
     */
    public function addEdge(Edge $edge)
    {
        return $this->createEdge($edge->getParentName(), $edge->getChildName());
    }

}