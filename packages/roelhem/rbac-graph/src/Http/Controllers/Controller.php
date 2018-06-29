<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 26-06-18
 * Time: 17:23
 */

namespace Roelhem\RbacGraph\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{

    use DispatchesJobs, ValidatesRequests, AuthorizesRequests;

}