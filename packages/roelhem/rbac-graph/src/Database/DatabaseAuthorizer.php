<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 25-06-18
 * Time: 06:33
 */

namespace Roelhem\RbacGraph\Database;


use Roelhem\RbacGraph\Contracts\Authorizable;
use Roelhem\RbacGraph\Contracts\Authorizer;
use Roelhem\RbacGraph\Contracts\Node;
use Roelhem\RbacGraph\Database\Traits\BelongsToDatabaseGraph;
use Roelhem\RbacGraph\Exceptions\NodeNotFoundException;

class DatabaseAuthorizer implements Authorizer
{

    use BelongsToDatabaseGraph;

    /**
     * @var Authorizable
     */
    protected $authorizable;

    /**
     * DatabaseAuthorizer constructor.
     * @param Authorizable $authorizable
     */
    public function __construct(Authorizable $authorizable)
    {
        $this->authorizable = $authorizable;
    }

    /**
     * Returns the authorizable object which is authorized by this authorizer.
     *
     * @return Authorizable
     */
    public function getAutorizable()
    {
        return $this->authorizable;
    }

    /**
     * Authorizes the provided node and returns the verdict.
     *
     * @param Node|string|integer $node
     * @param array $attributes
     * @return boolean
     * @throws NodeNotFoundException
     */
    public function authorize($node, $attributes)
    {
        $graph = $this->getGraph();
        $authorizable = $this->getAutorizable();

        $entryNodeIds = $graph->getEntryNodes($authorizable)->pluck('id');

        $query = Path::endsAt($node)->whereIn('first_node_id',$entryNodeIds);

        return $query->exists();
    }
}