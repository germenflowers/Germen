<?php

namespace Germen\Admin;

/**
 * Class Content
 * @package Germen\Admin
 */
class Content
{
    /**
     * Content constructor.
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
}
