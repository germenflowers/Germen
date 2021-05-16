<?php

namespace Germen\Admin;

use \Bitrix\Main\SystemException;
use \Bitrix\Main\ArgumentException;
use \Bitrix\Main\ObjectPropertyException;

/**
 * Class Rights
 * @package Germen\Admin
 */
class Rights
{
    private $settings = array(
        'admin-section' => array(
            'full' => true,
//            'orders' => array('full', 'view', 'edit'),
//            'history' => array('full', 'view', 'edit'),
//            'calendar' => array('full', 'view', 'edit'),
        ),
        'admin-section-view' => array(
            'full' => false,
            'orders' => array('view'),
            'history' => array('view'),
            'calendar' => array('view'),
        ),
    );

    /**
     * Rights constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param string $type
     * @param string $mode
     * @param int $userId
     * @return bool
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function checkRights(string $type, string $mode, int $userId = 0): bool
    {
        if (empty($userId)) {
            global $USER;
            $userId = (int)$USER->GetID();
        }

        if (empty($userId)) {
            return false;
        }

        $groups = $this->getRightsUserGroups($userId);

        if (empty($groups)) {
            return false;
        }

        foreach ($groups as $group) {
            if ($this->settings[$group['code']]['full']) {
                return true;
            }

            if (!empty($this->settings[$group['code']][$type]) &&
                in_array($mode, $this->settings[$group['code']][$type], true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $mode
     * @return bool
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function checkOrdersRights(string $mode): bool
    {
        return $this->checkRights('orders', $mode);
    }

    /**
     * @param string $mode
     * @return bool
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function checkHistoryRights(string $mode): bool
    {
        return $this->checkRights('history', $mode);
    }

    /**
     * @param string $mode
     * @return bool
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function checkCalendarRights(string $mode): bool
    {
        return $this->checkRights('calendar', $mode);
    }

    /**
     * @param int $userId
     * @return array
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function getRightsUserGroups(int $userId): array
    {
        $items = array();

        $user = new User();
        $userGroups = $user->getUserGroups($userId);

        foreach ($this->settings as $userGroupCode => $rights) {
            foreach ($userGroups as $group) {
                if ($userGroupCode === $group['code']) {
                    $items[$userGroupCode] = $group;

                    break;
                }
            }
        }

        return $items;
    }
}
