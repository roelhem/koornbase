<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 13-06-18
 * Time: 20:47
 */

namespace Roelhem\RbacGraph\Database;



use Roelhem\RbacGraph\Contracts\Edge as EdgeContract;
use Roelhem\RbacGraph\Contracts\MutableGraph;
use Roelhem\RbacGraph\Contracts\Node as NodeContract;
use Roelhem\RbacGraph\Database\Traits\Graph\GraphContractImplementation;
use Roelhem\RbacGraph\Database\Traits\Graph\MutableGraphContractImplementation;


/**
 * Class DatabaseGraph
 *
 * The graph object that handles the graph in the database.
 *
 * @package Roelhem\RbacGraph\Database
 */
class DatabaseGraph implements MutableGraph
{

    use GraphContractImplementation;
    use MutableGraphContractImplementation;



}