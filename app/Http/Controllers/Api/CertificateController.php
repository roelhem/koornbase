<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 15-08-18
 * Time: 13:10
 */

namespace App\Http\Controllers\Api;


class CertificateController extends Controller
{
    protected $eagerLoadForShow = ['person','category'];
}