<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 24-06-18
 * Time: 21:24
 */

namespace Roelhem\RbacGraph\Database\Observers;


use Roelhem\RbacGraph\Database\Edge;
use Roelhem\RbacGraph\Database\Path;
use Roelhem\RbacGraph\Exceptions\GraphCycleException;

class EdgeObserver
{

    /**
     * @param Edge $edge
     * @throws GraphCycleException
     */
    public function creating(Edge $edge) {

        if(Path::between($edge->child_id, $edge->parent_id)->exists()) {
            throw new GraphCycleException("You can't add a path from $edge->parent_id to $edge->child_id because there already exists a path from $edge->child_id to $edge->parent_id. Adding this edge will result in a cycle.");
        }

    }

    /**
     * @param Edge $edge
     */
    public function created(Edge $edge) {

        $edgePath = Path::createFromEdge($edge);

        $prePaths  = Path::endsAt($edge->getParentId())->where('size','>', 1)->get();
        $postPaths = Path::startsAt($edge->getChildId())->where('size','>', 1)->get();

        foreach ($postPaths as $postPath) {
            Path::createConcat($edgePath, $postPath);
        }

        foreach ($prePaths as $prePath) {
            $newPath = Path::createConcat($prePath, $edgePath);
            foreach ($postPaths as $postPath) {
                Path::createConcat($newPath, $postPath);
            }
        }
    }

    /**
     * @param Edge $edge
     */
    public function deleted(Edge $edge) {
        Path::edge($edge)->delete();
    }

}