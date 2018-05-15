<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 30-04-18
 * Time: 10:33
 */

namespace App\Traits;

use Cviebrock\EloquentSluggable\Sluggable as SluggableParent;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

trait Sluggable
{
    use SluggableParent;
    use SluggableScopeHelpers;

    /**
     * The sluggable settings function.
     *
     * @return array
     */
    public function sluggable(): array
    {

        return [
            'slug' => [
                'source' => $this->getSluggableSource(),
                'maxLength' => 63,
                'maxLengthKeepWords' => true,
                'method' => null,
                'separator' => '-',
                'unique' => true,
                'uniqueSuffix' => null,
                'includeTrashed' => true,
                'reserved' => null,
                'onUpdate' => true
            ]
        ];
    }

    /**
     * Determines which attribute or attributes should be used to generate the slug.
     */
    protected function getSluggableSource() {
        if(in_array(HasShortName::class, class_uses($this))) {
            return 'name_short';
        } else {
            return 'name';
        }
    }

}