<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 12-06-18
 * Time: 23:03
 */

namespace Roelhem\RbacGraph\Graphs;


use Roelhem\RbacGraph\Contracts\MutableGraph;
use Roelhem\RbacGraph\Contracts\Traits\GraphDefaultContains;
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
    public function createNode($type, $name, $options = [], $id = null)
    {
        $node = new SimpleNode($this,NodeType::by($type),$this->idGen->next(), $name, $options);
        $this->storeNode($node);
        return $node;
    }

    /**
     * @inheritdoc
     */
    public function createEdge($parent, $child)
    {
        $edge = new SimpleEdge($this, $parent, $child);
        $this->storeEdge($edge);
        return $edge;
    }

}