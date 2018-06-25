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

class EdgeObserver
{


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

    public function deleted(Edge $edge) {
        Path::edge($edge)->delete();
    }

}