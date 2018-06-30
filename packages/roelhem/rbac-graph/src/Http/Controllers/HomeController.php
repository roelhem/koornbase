<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 26-06-18
 * Time: 17:22
 */

namespace Roelhem\RbacGraph\Http\Controllers;



use Roelhem\RbacGraph\Contracts\Graphs\Graph;

class HomeController extends Controller
{

    public function index(Graph $graph) {
        return view('rbac-graph::index', [
            'graph' => $graph
        ]);
    }

}