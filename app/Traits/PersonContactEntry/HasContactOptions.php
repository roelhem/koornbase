<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 30-05-18
 * Time: 14:10
 */

namespace App\Traits\PersonContactEntry;


use App\Traits\HasOptions;

trait HasContactOptions
{
    use HasOptions;

    /**
     * @inheritdoc
     */
    protected function defaultOptions(): array
    {
        return [
            'is_primary' => false,
            'for_emergency' => false,
        ];
    }
}