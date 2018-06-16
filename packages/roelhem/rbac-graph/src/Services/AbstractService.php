<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 14-06-18
 * Time: 01:19
 */

namespace Roelhem\RbacGraph\Services;

use Roelhem\RbacGraph\Contracts\RbacService;
use Roelhem\RbacGraph\Services\Traits\BuilderShortcutsImplementation;

abstract class AbstractService implements RbacService
{

    use BuilderShortcutsImplementation;

    // ---------------------------------------------------------------------------------------------------------- //
    // --------  BUILDER METHODS  ------------------------------------------------------------------------------- //
    // ---------------------------------------------------------------------------------------------------------- //

    /**
     * @inheritdoc
     */
    public function get(string $name)
    {
        return $this->builder()->get($name);
    }

    /**
     * @inheritdoc
     */
    public function create($type, string $name)
    {
        return $this->builder()->create($type, $name);
    }

    /**
     * @inheritdoc
     */
    public function node($type, string $name)
    {
        return $this->builder()->node($type, $name);
    }

    /**
     * @inheritdoc
     */
    public function group(string $prefix, callable $definitions)
    {
        $this->builder()->group($prefix, $definitions);
    }

    /**
     * @inheritdoc
     */
    public function edge($parent, $child)
    {
        return $this->builder()->edge($parent, $child);
    }
}