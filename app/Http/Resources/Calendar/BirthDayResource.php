<?php

namespace App\Http\Resources\Calendar;

use App\Helpers\Calendar\CalendarEventHelper;
use App\Http\Resources\Display\PersonResource;
use Carbon\Carbon;

class BirthDayResource extends Resource
{

    protected $type = 'person-birth-day';
    protected $allDay = true;

    /**
     * @inheritdoc
     */
    protected function getIcons()
    {
        return [
            'fa' => 'birthday-cake'
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getEventId()
    {
        return 'birth-day-'.$this->search_start->year.'-person-'.$this->id;
    }

    /**
     * Gives the age of the Person at this birthday.
     *
     * @return int
     */
    protected function getAge()
    {
        return $this->search_start->diffInYears($this->birth_date) + 1;
    }

    /**
     * @inheritdoc
     */
    protected function getStart()
    {
        $result = clone $this->birth_date;
        $result->addYears($this->getAge());
        return $result;
    }

    /**
     * @inheritdoc
     */
    protected function getEnd()
    {
        $result = clone $this->getStart();
        $result->addDay();
        return $result;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $this->getSearchParameters($request);

        $this->style['muted'] = true;

        return parent::toArray($request) + [
            'person' => new PersonResource($this->resource),
            'turned_age' => $this->getAge(),
        ];
    }
}
