<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 25-06-18
 * Time: 01:54
 */

namespace Roelhem\RbacGraph\Database\Traits\Path;

use Illuminate\Database\Eloquent\Collection;
use Roelhem\RbacGraph\Database\Node;
use Roelhem\RbacGraph\Database\Path;

/**
 * Trait PathRelations
 * @package Roelhem\RbacGraph\Database\Traits\Path
 *
 * @property-read Node $firstNode
 * @property-read Node $lastNode
 * @property-read Path|null $firstPath
 * @property-read Path|null $lastPath
 * @property-read Collection|Path[] $asFirstDependingPaths
 * @property-read Collection|Path[] $asLastDependingPaths
 */
trait PathRelations
{

    /**
     * Gives the first node of this path.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function firstNode() {
        return $this->belongsTo(Node::class, 'first_node_id');
    }

    /**
     * Gives the last node of this path.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lastNode() {
        return $this->belongsTo(Node::class, 'last_node_id');
    }

    /**
     * Gives the first dependent path.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function firstPath() {
        return $this->belongsTo(Path::class, 'first_path_id');
    }

    /**
     * Gives the last dependent path.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lastPath() {
        return $this->belongsTo(Path::class, 'last_path_id');
    }

    /**
     * Gives all the paths that are first-dependent on this path.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function asFirstDependingPaths() {
        return $this->hasMany(Path::class, 'first_path_id');
    }

    /**
     * Gives all the paths that are last-dependent on this path.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function asLastDependingPaths() {
        return $this->hasMany(Path::class, 'last_path_id');
    }

}