<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 30-04-18
 * Time: 10:46
 */

namespace App\Traits;

use Cviebrock\EloquentSluggable\Sluggable as SluggableParent;

trait HasStringPrimaryKey
{
    use SluggableParent;

    /**
     * The sluggable settings function.
     *
     * @return array
     */
    public function sluggable(): array
    {

        return [
            'id' => [
                'source' => $this->getSluggableSource(),
                'maxLength' => 63,
                'maxLengthKeepWords' => true,
                'method' => null,
                'separator' => '-',
                'unique' => true,
                'uniqueSuffix' => null,
                'includeTrashed' => true,
                'reserved' => null,
                'onUpdate' => false
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