<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 30-06-18
 * Time: 11:38
 */

namespace Roelhem\RbacGraph\Http\Controllers;


use Roelhem\RbacGraph\Contracts\Graphs\Graph;

class NodeController extends Controller
{

    public function index(Graph $graph) {
        return view('rbac-graph::nodes.index', [
            'nodes' => $graph->getNodes()
        ]);
    }


    /**
     * @param $node
     * @param Graph $graph
     * @throws
     */
    public function view($node, Graph $graph) {
        $node = $graph->getNodeById($node);

        return view('rbac-graph::nodes.view', [
            'node' => $node,
            'incomingEdges' => $graph->getIncomingEdges($node),
            'outgoingEdges' => $graph->getOutgoingEdges($node)
        ]);
    }

}