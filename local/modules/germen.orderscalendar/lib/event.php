<?php

namespace Germen\Orderscalendar;

use Exception;

/**
 * Class Event
 * @package Germen\Orderscalendar
 */
class Event
{
    public const ALL_DAY_REGEX = '/^\d{4}-\d\d-\d\d$/';

    public $title;
    public $allDay;
    public $start;
    public $end;
    public $properties = array();

    /**
     * Event constructor.
     * @param array $array
     * @param null $timeZone
     * @throws Exception
     */
    public function __construct(array $array, $timeZone = null)
    {
        $this->title = $array['title'];

        if (isset($array['allDay'])) {
            $this->allDay = (bool)$array['allDay'];
        } else {
            $this->allDay = preg_match(self::ALL_DAY_REGEX, $array['start']) &&
                (!isset($array['end']) || preg_match(self::ALL_DAY_REGEX, $array['end']));
        }

        if ($this->allDay) {
            $timeZone = null;
        }

        $this->start = Tools::parseDateTime($array['start'], $timeZone);
        $this->end = isset($array['end']) ? Tools::parseDateTime($array['end'], $timeZone) : null;

        foreach ($array as $name => $value) {
            if (!in_array($name, array('title', 'allDay', 'start', 'end'))) {
                $this->properties[$name] = $value;
            }
        }
    }

    /**
     * @param $rangeStart
     * @param $rangeEnd
     * @return bool
     * @throws Exception
     */
    public function isWithinDayRange($rangeStart, $rangeEnd): bool
    {
        $eventStart = Tools::stripTime($this->start);

        if (isset($this->end)) {
            $eventEnd = Tools::stripTime($this->end);
        } else {
            $eventEnd = $eventStart;
        }

        return $eventStart < $rangeEnd && $eventEnd >= $rangeStart;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $array = $this->properties;

        $array['title'] = $this->title;

        if ($this->allDay) {
            $format = 'Y-m-d';
        } else {
            $format = 'c';
        }

        $array['start'] = $this->start->format($format);
        if (isset($this->end)) {
            $array['end'] = $this->end->format($format);
        }

        return $array;
    }
}
