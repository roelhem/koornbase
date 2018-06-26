<?php

namespace Roelhem\RbacGraph\Database\Traits\Path;

use Roelhem\RbacGraph\Database\Path;
use Roelhem\RbacGraph\Contracts\Nodes\Node as NodeContract;
use Roelhem\RbacGraph\Contracts\Edges\Edge as EdgeContract;
use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;

trait PathStaticCreators
{

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

        $nodeId = $path->getGraph()->getNodeId($node);

        $path->first_node_id = $nodeId;
        $path->last_node_id = $nodeId;
        $path->size = 1;
        $path->path = [$nodeId];
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

        $parent_path_id = Path::singleton($parent_id)->value('id');
        $child_path_id = Path::singleton($child_id)->value('id');

        $path = new Path();

        $path->first_node_id = $parent_id;
        $path->last_node_id = $child_id;

        $path->first_path_id = $parent_path_id;
        $path->last_path_id = $child_path_id;

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