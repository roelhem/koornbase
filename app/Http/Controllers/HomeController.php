<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 21-07-18
 * Time: 19:28
 */

namespace App\Http\Controllers;


class HomeController extends Controller
{

    /**
     * The action that handles a call to the root of the website.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function index()
    {
        return view('index');
    }

    function about()
    {
        return view('about');
    }

    function apps()
    {
        return view('apps');
    }

    function developers()
    {
        return view('developers');
    }

}