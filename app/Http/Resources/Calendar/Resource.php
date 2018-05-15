<?php

namespace App\Http\Resources\Calendar;

use App\Enums\BootstrapColors;
use App\Helpers\Calendar\CalendarEventHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;

class Resource extends JsonResource
{

    protected $type = 'default';

    protected $allDay = false;

    protected $search_start;

    protected $search_end;

    protected $style = [
        'muted' => false,
    ];

    protected $classes = [];


    /**
     * Creates and returns an object for the icon of the CalendarEvent.
     *
     * @return array
     */
    protected function getIcons() {
        return [];
    }

    /**
     * Creates and returns the color that the icon should be.
     *
     * @return string
     */
    protected function getIconColor() {
        return null;
    }

    /**
     * Gives an array with classes for the icon.
     *
     * @return array
     */
    protected function getIconClasses() {
        $res = [];

        $iconColor = $this->getIconColor();

        if($iconColor !== null) {
            $res[] = BootstrapColors::getTextClass($iconColor);
        }

        return $res;
    }

    /**
     * Gives the color of the CalendarEvent.
     */
    protected function getColor() {
        return null;
    }

    /**
     * Gives the color of the border of the CalendarEvent.
     *
     * @return string|null
     */
    protected function getBackgroundColor() {
        return null;
    }

    /**
     * Gives the color of the border of the CalendarEvent.
     *
     * @return string|null
     */
    protected function getBorderColor() {
        return null;
    }

    /**
     * Gives the color of the border of the CalendarEvent.
     *
     * @return string|null
     */
    protected function getTextColor() {
        return null;
    }

    /**
     * Creates and returns an id that is unique for all CalendarEvents and thus be used as the id of
     * the CalendarEvent.
     *
     * @return string
     */
    protected function getEventId() {
        return $this->type.'-'.$this->resource->id;
    }

    /**
     * Creates and returns a title for this CalendarEvent.
     *
     * @return string
     */
    protected function getTitle() {
        $resource = $this->resource;
        if($resource instanceof Model) {
            if(isset($resource->title)) {
                return strval($resource->title);
            } elseif(isset($resource->name_short)) {
                return strval($resource->name_short);
            } elseif(isset($resource->name)) {
                return strval($resource->name);
            }
            return strval($resource->id);
        }
        return strval($this->type);
    }

    /**
     * Finds and returns the start DateTime of this CalendarEvent
     *
     * @return Carbon
     * @throws
     */
    protected function getStart() {
        $resource = $this->resource;
        if($resource instanceof Model) {
            if(isset($resource->start)) {
                if($resource->start instanceof Carbon) {
                    return $resource->start;
                } else {
                    return Carbon::parse($resource->start);
                }
            }
        }
        throw new \ErrorException('Can\'t find a start value for this CalenderEventResource.');
    }

    /**
     * Finds and returns the end DateTime of this CalendarEvent
     *
     * @return Carbon
     * @throws \ErrorException
     */
    protected function getEnd() {
        $resource = $this->resource;
        if($resource instanceof Model) {
            if(isset($resource->end)) {
                if($resource->end instanceof Carbon) {
                    return $resource->end;
                } else {
                    return Carbon::parse($resource->end);
                }
            }
        }
        throw new \ErrorException('Can\'t find a end value for this CalenderEventResource.');
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     * @throws
     */
    public function toArray($request)
    {

        $icons = $this->getIcons();
        $iconClasses = $this->getIconClasses();

        $backgroundColor = $this->getBackgroundColor();
        $borderColor = $this->getBorderColor();
        $textColor = $this->getTextColor();

        $res = [
            'type' => $this->type,
            'id' => $this->getEventId(),
            'title' => $this->getTitle(),
            'description' => $this->when(isset($this->resource->description), $this->resource->description),
            'allDay' => $this->allDay,
            'start' => $this->allDay ? $this->getStart()->toDateString() : $this->getStart()->format('c'),
            'end' => $this->allDay ? $this->getEnd()->toDateString() : $this->getEnd()->format('c'),
            'icons' => $this->when(count($icons) > 0, $icons),
            'className' => $this->getClasses(),
            'iconClassName' => $this->when(count($iconClasses) > 0, $iconClasses),
        ];

        $this->addColorVar($res, 'color');
        $this->addColorVar($res, 'backgroundColor');
        $this->addColorVar($res, 'borderColor');
        $this->addColorVar($res, 'textColor');

        return $res;
    }

    private function addColorVar(&$res, $key) {
        $functionName = 'get'.ucfirst($key);
        $color = call_user_func([$this, $functionName]);
        if(is_string($color)) {
            $res[$key] = BootstrapColors::getCssVar($color);
        }
    }

    /**
     * Returns an array of classes that will be added to the CalendarEvent.
     *
     * @return array
     */
    protected function getClasses()
    {
        $res = $this->classes;

        if($this->style['muted']) {
            $res[] = 'fc-muted';
        }

        $res[] = 'kb-event-'.$this->type;

        return $res;
    }

    /**
     * Sets the $search_start and $search_end to the proper Cabon instances.
     *
     * @param $request
     */
    protected function getSearchParameters($request) {
        list($start, $end) = CalendarEventHelper::parseGetRequest($request);

        $this->search_start = $start;
        $this->search_end = $end;
    }
}
