<?php

namespace Germen\Handlers;

/**
 * Class Main
 * @package Germen\Handlers
 */
class Main
{
    /**
     * @param array $fields
     */
    public static function OnAfterUserAdd(array &$fields): void
    {
        self::setUserLoginLikeEmail($fields);
    }

    /**
     * @param array $fields
     */
    public static function OnAfterUserUpdate(array &$fields): void
    {
        self::setUserLoginLikeEmail($fields);
    }

    /**
     * @param array $userField
     * @return bool
     */
    private static function setUserLoginLikeEmail(array $userField): bool
    {
        if (empty((int)$userField['ID'])) {
            return false;
        }

        if (empty($userField['EMAIL'])) {
            return false;
        }

        if ($userField['EMAIL'] === $userField['LOGIN']) {
            return true;
        }

        return (bool)(new \CUser())->Update((int)$userField['ID'], array('LOGIN' => $userField['EMAIL']));
    }
}
