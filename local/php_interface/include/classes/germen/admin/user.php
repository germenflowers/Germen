<?php

namespace Germen\Admin;

use \Bitrix\Main\UserGroupTable;
use \Bitrix\Main\SystemException;
use \Bitrix\Main\ArgumentException;
use \Bitrix\Main\ObjectPropertyException;

/**
 * Class User
 * @package Germen\Admin
 */
class User
{
    /**
     * User constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param int $userId
     * @return array
     */
    public function getUserData(int $userId = 0): array
    {
        $data = array(
            'id' => 0,
            'login' => '',
            'email' => '',
            'name' => '',
            'surname' => '',
            'letter' => '',
        );

        if (empty($userId)) {
            global $USER;
            $userId = (int)$USER->GetID();
        }

        if (empty($userId)) {
            return $data;
        }

        $by = 'ID';
        $order = 'ASC';
        $filter = array('ID' => $userId);
        $params = array('FIELDS' => array('ID', 'LOGIN', 'EMAIL', 'NAME', 'LAST_NAME'));
        $result = \CUser::GetList($by, $order, $filter, $params);
        if ($row = $result->Fetch()) {
            $data = array(
                'id' => (int)$row['ID'],
                'login' => trim($row['LOGIN']),
                'email' => trim($row['EMAIL']),
                'name' => ucfirst(trim($row['NAME'])),
                'surname' => ucfirst(trim($row['LAST_NAME'])),
                'letter' => $this->getUserLetter(trim($row['EMAIL']), trim($row['NAME'])),
            );
        }

        return $data;
    }

    /**
     * @param string $email
     * @param string $name
     * @return string
     */
    public function getUserLetter(string $email, string $name): string
    {
        $letter = '';

        if (!empty($name)) {
            $letter = mb_substr($name, 0, 1);
        } elseif (!empty($email)) {
            $letter = mb_substr($email, 0, 1);
        }

        return strtoupper($letter);
    }

    /**
     * @param string $key
     * @return array
     */
    public function getGroupsList(string $key = 'id'): array
    {
        $groups = array();

        $by = 'ID';
        $order = 'ASC';
        $filter = array();
        $result = \CGroup::GetList($by, $order, $filter);
        while ($row = $result->Fetch()) {
            $arrayKey = (int)$row['ID'];
            if ($key === 'code' && !empty($row['STRING_ID'])) {
                $arrayKey = $row['STRING_ID'];
            }

            $groups[$arrayKey] = array(
                'id' => (int)$row['ID'],
                'code' => $row['STRING_ID'],
                'name' => $row['NAME'],
                'active' => $row['ACTIVE'] === 'Y',
            );
        }

        return $groups;
    }

    /**
     * @param int $userId
     * @return array
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function getUserGroups(int $userId): array
    {
        $userGroups = array();

        $groupsList = $this->getGroupsList();

        $result = UserGroupTable::getList(
            array(
                'filter' => array('USER_ID' => $userId),
                'select' => array('GROUP_ID'),
            )
        );
        while ($row = $result->fetch()) {
            $userGroups[(int)$row['GROUP_ID']] = $groupsList[(int)$row['GROUP_ID']];
        }

        return $userGroups;
    }
}
