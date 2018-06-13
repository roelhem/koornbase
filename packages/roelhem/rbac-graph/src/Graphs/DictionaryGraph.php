<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 12-06-18
 * Time: 23:03
 */

namespace Roelhem\RbacGraph\Graphs;


use Roelhem\RbacGraph\Contracts\Graph;
use Roelhem\RbacGraph\Contracts\Traits\HasEdgeDictionaries;
use Roelhem\RbacGraph\Contracts\Traits\HasNodeDictionaries;

class DictionaryGraph implements Graph
{

    use HasNodeDictionaries;
    use HasEdgeDictionaries;

    public function equals($other): bool
    {
        return $this === $other;
    }

}