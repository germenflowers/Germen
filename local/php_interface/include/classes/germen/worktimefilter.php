<?php

namespace Germen;

/**
 * Class WorkTimeFilter
 * @package Germen
 */
class WorkTimeFilter
{
    private $iblockId = IBLOCK_ID__CATALOG;
    private $weekDays;
    private $weekDaysMap = array(
        1 => 'monday',
        2 => 'tuesday',
        3 => 'wednesday',
        4 => 'thursday',
        5 => 'friday',
        6 => 'saturday',
        7 => 'sunday',
    );

    /**
     * WorkTimeFilter constructor.
     */
    public function __construct()
    {
        $this->weekDays = Tools::returnResultCache(
            60 * 60,
            'getWeekDays',
            array($this, 'getWeekDays')
        );
    }

    /**
     * @return array
     */
    public function getWeekDays(): array
    {
        $items = array();

        $order = array('SORT' => 'ASC');
        $filter = array('IBLOCK_ID' => $this->iblockId, 'CODE' => 'ACTIVE_WEEK_DAYS');
        $result = \CIBlockPropertyEnum::GetList($order, $filter);
        while ($row = $result->Fetch()) {
            $items[$row['XML_ID']] = array(
                'id' => $row['ID'],
                'xmlId' => $row['XML_ID'],
                'value' => $row['VALUE'],
            );
        }

        return $items;
    }

    /**
     * @param bool $strict
     * @return array
     */
    public function getFilter(bool $strict = false): array
    {
        $filter = array();

        if (empty($_REQUEST['time'])) {
            return $filter;
        }

        $time = strtotime($_REQUEST['time']);
        if (empty($time)) {
            return $filter;
        }

        $filter = array_merge($filter, $this->getEndDateFilter($time, $strict));
        $filter = array_merge($filter, $this->getShiftFilter($time, $strict));
        $filter = array_merge($filter, $this->getWeekDayFilter($time, $strict));

        return $filter;
    }

    /**
     * @param int $time
     * @param bool $strict
     * @return array
     */
    public function getEndDateFilter(int $time, bool $strict = false): array
    {
        if ($strict) {
            $filter = array(
                '!PROPERTY_ACTIVE_END_DATE' => false,
                '>=PROPERTY_ACTIVE_END_DATE' => date('Y-m-d', $time),
            );
        } else {
            $filter = array(
                array(
                    'LOGIC' => 'OR',
                    array('PROPERTY_ACTIVE_END_DATE' => false),
                    array('>=PROPERTY_ACTIVE_END_DATE' => date('Y-m-d', $time)),
                ),
            );
        }

        return $filter;
    }

    /**
     * @param int $time
     * @param bool $strict
     * @return array
     */
    public function getShiftFilter(int $time, bool $strict = false): array
    {
        if ($strict) {
            $filter = array(
                '!PROPERTY_ACTIVE_START_SHIFT' => false,
                '!PROPERTY_ACTIVE_END_SHIFT' => false,
                '<=PROPERTY_ACTIVE_START_SHIFT' => date('H:i', $time),
                '>=PROPERTY_ACTIVE_END_SHIFT' => date('H:i', $time),
            );
        } else {
            $filter = array(
                array(
                    'LOGIC' => 'OR',
                    array('PROPERTY_ACTIVE_START_SHIFT' => false),
                    array('<=PROPERTY_ACTIVE_START_SHIFT' => date('H:i', $time)),
                ),
                array(
                    'LOGIC' => 'OR',
                    array('PROPERTY_ACTIVE_END_SHIFT' => false),
                    array('>=PROPERTY_ACTIVE_END_SHIFT' => date('H:i', $time)),
                ),
            );
        }

        return $filter;
    }

    /**
     * @param int $time
     * @param bool $strict
     * @return array
     */
    public function getWeekDayFilter(int $time, bool $strict = false): array
    {
        $weekDay = date('N', $time);

        if (empty($this->weekDaysMap[$weekDay])) {
            return array();
        }

        if (empty($this->weekDays[$this->weekDaysMap[$weekDay]]['id'])) {
            return array();
        }

        if ($strict) {
            $filter = array('PROPERTY_ACTIVE_WEEK_DAYS' => $this->weekDays[$this->weekDaysMap[$weekDay]]['id']);
        } else {
            $filter = array(
                array(
                    'LOGIC' => 'OR',
                    array('PROPERTY_ACTIVE_WEEK_DAYS' => false),
                    array('PROPERTY_ACTIVE_WEEK_DAYS' => $this->weekDays[$this->weekDaysMap[$weekDay]]['id']),
                ),
            );
        }

        return $filter;
    }
}
