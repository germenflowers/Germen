<?php

/**
 * Class onAdminSaleOrderViewDraggable
 */
class onAdminSaleOrderViewDraggable
{
    /**
     * @return array
     */
    public static function onInit(): array
    {
        return array(
            'BLOCKSET' => 'onAdminSaleOrderViewDraggable',
            'check' => array('onAdminSaleOrderViewDraggable', 'check'),
            'action' => array('onAdminSaleOrderViewDraggable', 'action'),
            'getScripts' => array('onAdminSaleOrderViewDraggable', 'getScripts'),
            'getBlocksBrief' => array('onAdminSaleOrderViewDraggable', 'getBlocksBrief'),
            'getBlockContent' => array('onAdminSaleOrderViewDraggable', 'getBlockContent'),
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


        global $APPLICATION;
        $APPLICATION->RestartBuffer();
        echo '<pre>'.print_r($blockCode, true).'</pre>';
        echo '<pre>'.print_r($selectedTab, true).'</pre>';
        echo '<pre>'.print_r($args, true).'</pre>';
        die();

        if ($selectedTab === 'tab_order') {

        }

        return $result;
    }
}
