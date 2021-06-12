<?php

/**
 * Class onAdminSaleOrderEditDraggable
 */
class onAdminSaleOrderEditDraggable
{
    /**
     * @return array
     */
    public static function onInit(): array
    {
        return array(
            'BLOCKSET' => 'onAdminSaleOrderEditDraggable',
            'check' => array('onAdminSaleOrderEditDraggable', 'check'),
            'action' => array('onAdminSaleOrderEditDraggable', 'action'),
            'getScripts' => array('onAdminSaleOrderEditDraggable', 'getScripts'),
            'getBlocksBrief' => array('onAdminSaleOrderEditDraggable', 'getBlocksBrief'),
            'getBlockContent' => array('onAdminSaleOrderEditDraggable', 'getBlockContent'),
        );
    }

    /**
     * @param array $args
     * @return bool
     */
    public static function check(array $args): bool
    {
        return true;
    }

    /**
     * @param array $args
     * @return bool
     * @throws Exception
     */
    public static function action(array $args): bool
    {
        $id = !empty($args['ORDER']) ? (int)$args['ORDER']->getId() : 0;

        return true;
    }

    /**
     * @param array $args
     * @return string
     */
    public static function getScripts(array $args): string
    {
        return '<script type="text/javascript"></script>';
    }

    /**
     * @param array $args
     * @return array
     */
    public static function getBlocksBrief(array $args): array
    {
        $id = !empty($args['ORDER']) ? (int)$args['ORDER']->getId() : 0;

        return array();
    }

    /**
     * @param string $blockCode
     * @param string $selectedTab
     * @param array $args
     * @return string
     */
    public static function getBlockContent(string $blockCode, string $selectedTab, array $args): string
    {
        $result = '';

        $id = !empty($args['ORDER']) ? (int)$args['ORDER']->getId() : 0;
        $userId = !empty($args['ORDER']) ? (int)$args['ORDER']->getUserId() : 0;

        if ($selectedTab === 'tab_order') {
        }

        return $result;
    }
}
