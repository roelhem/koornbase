<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-08-18
 * Time: 13:10
 */

namespace App\Http\Controllers\Api;


use App\Certificate;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    protected $eagerLoadForShow = ['person','category'];


    public function store(Request $request)
    {


    }
}