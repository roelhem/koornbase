<?php
/**
 * Created by PhpStorm.
 * User: roel
 * Date: 14-05-18
 * Time: 12:38
 */

namespace App\Http\Resources\Calendar;


class EventResource extends Resource
{

    protected $type = 'event';
    protected $allDay = false;

    protected function getIcons()
    {
        return $this->category->options['icons'];
    }

    protected function getColor()
    {
        return $this->category->options['color'];
    }

    protected function getIconColor()
    {
        return $this->category->options['iconColor'];
    }

    protected function getBorderColor()
    {
        return $this->category->options['borderColor'];
    }

    protected function getTextColor()
    {
        return $this->category->options['textColor'];
    }

    protected function getBackgroundColor()
    {
        return $this->category->options['backgroundColor'];
    }

    public function toArray($request)
    {
        return parent::toArray($request) + [
            'is_open' => $this->is_open,
        ];
    }

}