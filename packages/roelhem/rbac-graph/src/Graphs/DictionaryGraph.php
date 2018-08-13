<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 12-06-18
 * Time: 23:03
 */

namespace Roelhem\RbacGraph\Graphs;


use Roelhem\RbacGraph\Services\Builders\RbacBuilder;
use Roelhem\RbacGraph\Contracts\Edges\Edge;
use Roelhem\RbacGraph\Contracts\Graphs\MutableGraph;
use Roelhem\RbacGraph\Contracts\Nodes\Node;
use Roelhem\RbacGraph\Graphs\Traits\GraphDefaultContains;
use Roelhem\RbacGraph\Graphs\Traits\GraphDefaultEquals;
use Roelhem\RbacGraph\Graphs\Traits\HasAssignmentArray;
use Roelhem\RbacGraph\Graphs\Traits\HasEdgeDictionaries;
use Roelhem\RbacGraph\Graphs\Traits\HasNodeDictionaries;
use Roelhem\RbacGraph\Graphs\Edges\SimpleEdge;
use Roelhem\RbacGraph\Enums\NodeType;
use Roelhem\RbacGraph\Helpers\IdGenerator;
use Roelhem\RbacGraph\Graphs\Nodes\SimpleNode;

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