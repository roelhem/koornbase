<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 13-06-18
 * Time: 06:41
 */

namespace Roelhem\RbacGraph\Contracts\Traits;


trait HasIdSequenceGenerator
{

    /**
     * @var integer|null
     */
    protected $idSequence;

    /**
     * @return int
     */
    protected function getFirstId() {
        return count($this->getNodes());
    }

    /**
     * @return int
     */
    protected function getNextId() {
        if($this->idSequence === null) {
            $this->idSequence = intval($this->getFirstId());
        } else {
            $this->idSequence++;
        }

        if($this->hasNodeId($this->idSequence)) {
            return $this->getNextId();
        } else {
            return $this->idSequence;
        }
    }

}