<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 26-06-18
 * Time: 17:22
 */

namespace Roelhem\RbacGraph\Http\Controllers;



class HomeController extends Controller
{

    public function index() {
        return view('rbac-graph::index');
    }

}