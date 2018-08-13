<?php

namespace Roelhem\RbacGraph\Database\Traits\Path;

use Roelhem\RbacGraph\Contracts\Graphs\Graph;
use Roelhem\RbacGraph\Database\Path;
use Roelhem\RbacGraph\Contracts\Nodes\Node as NodeContract;
use Roelhem\RbacGraph\Contracts\Edges\Edge as EdgeContract;
use Roelhem\RbacGraph\Enums\NodeType;
use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;

trait PathStaticCreators
{

    /**
     * @param Graph $graph
     * @param iterable $nodes
     * @return array
     */
    private static function getRules(Graph $graph, iterable $nodes) {
        $res = [];
        foreach($nodes as $node) {
            try {
                $node = $graph->getNode($node);
                if ($node->getType()->is(NodeType::GATE)) {
                    $res[] = $node->getOption('rule');
                }
            } catch (NodeNotFoundException $exception) {

            }
        }
        return $res;
    }

    // ---------------------------------------------------------------------------------------------------------- //
    // ----- STATIC HELPER CREATORS ----------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * Creates a new singleton-path for the provided node.
     *
     * @param NodeContract|string|integer $node
     * @return Path
     * @throws NodeNotFoundException
     */
    public static function createSingleton( $node )
    {
        $path = new Path();

        $node = $path->getGraph()->getNode($node);

        $path->first_node_id = $node->getId();
        $path->last_node_id = $node->getId();
        $path->size = 1;
        $path->path = [$node];

        $path->save();
        return $path;
    }

    /**
     * Creates a new path from the provided edge.
     *
     * @param EdgeContract $edge
     * @return Path
     */
    public static function createFromEdge( $edge )
    {
        $parent_id = $edge->getParentId();
        $child_id = $edge->getChildId();

        $parentPath = Path::singleton($parent_id)->first();
        $childPath  = Path::singleton($child_id)->first();

        if(!($parentPath instanceof Path)) {
            trigger_error("The parent-node of the edge has no singleton path yet.");
        }
        if(!($childPath instanceof Path)) {
            trigger_error("The child-node of the edge has no singleton path yet.");
        }

        $path = new Path();

        $path->first_node_id = $parent_id;
        $path->last_node_id = $child_id;

        $path->first_path_id = $parentPath->id;
        $path->last_path_id = $childPath->id;

        $path->size = 2;
        $path->path = [$parent_id, $child_id];

        $path->save();

        return $path;
    }

    /**
     * Makes a new path by combining the two provided paths.
     *
     * @param Path|integer $firstPath
     * @param Path|integer $lastPath
     * @return Path
     */
    public static function makeConcat( $firstPath, $lastPath )
    {
        if(!($firstPath instanceof Path)) {
            $firstPath = Path::findOrFail($firstPath);
        }

        if(!($lastPath instanceof Path)) {
            $lastPath = Path::findOrFail($lastPath);
        }

        if($firstPath->last_node_id !== $lastPath->first_node_id) {
            throw new \InvalidArgumentException('The last node of the first path must be equal to the first node of the last path.');
        }

        $path = new Path();
        $path->first_node_id = $firstPath->first_node_id;
        $path->last_node_id = $lastPath->last_node_id;

        $path->first_path_id = $firstPath->id;
        $path->last_path_id = $lastPath->id;

        $path->size = $firstPath->size + $lastPath->size - 1;
        $path->path = array_merge($firstPath->path, array_slice($lastPath->path,1));

        return $path;
    }

    /**
     * Creates a new path by combining the two provided paths.
     *
     * @param Path|integer $firstPath
     * @param Path|integer $lastPath
     * @return Path
     * @throws
     */
    public static function createConcat( $firstPath, $lastPath )
    {
        $path = self::makeConcat($firstPath, $lastPath);
        $path->saveOrFail();
        return $path;
    }

}