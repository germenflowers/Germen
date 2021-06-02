<?php

use \Bitrix\Main\Loader;
use \Bitrix\Main\LoaderException;
use \Bitrix\Iblock\Component\Tools;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

/**
 * Class GermenAdminOrder
 */
class GermenAdminOrder extends CBitrixComponent
{
    /**
     * @param array $arParams
     * @return array
     */
    public function onPrepareComponentParams($arParams): array
    {
        if (empty($arParams['FILTER_NAME'])) {
            $arParams['FILTER_NAME'] = 'filter';
        }

        if (empty($arParams['PAGER_ID'])) {
            $arParams['PAGER_ID'] = 'admin-order';
        }

        if (empty($arParams['PAGE_ELEMENT_COUNT'])) {
            $arParams['PAGE_ELEMENT_COUNT'] = 10;
        }

        if (empty($arParams['DISPLAY_BOTTOM_PAGER'])) {
            $arParams['DISPLAY_BOTTOM_PAGER'] = 'N';
        }

        if (empty($arParams['DISPLAY_TOP_PAGER'])) {
            $arParams['DISPLAY_TOP_PAGER'] = 'N';
        }

        if (empty($arParams['PAGER_BASE_LINK_ENABLE'])) {
            $arParams['PAGER_BASE_LINK_ENABLE'] = 'N';
        }

        if (empty($arParams['PAGER_DESC_NUMBERING'])) {
            $arParams['PAGER_DESC_NUMBERING'] = 'N';
        }

        if (empty($arParams['PAGER_DESC_NUMBERING_CACHE_TIME'])) {
            $arParams['PAGER_DESC_NUMBERING_CACHE_TIME'] = 36000;
        }

        if (empty($arParams['PAGER_SHOW_ALL'])) {
            $arParams['PAGER_SHOW_ALL'] = 'N';
        }

        if (empty($arParams['PAGER_SHOW_ALWAYS'])) {
            $arParams['PAGER_SHOW_ALWAYS'] = 'N';
        }

        if (empty($arParams['SEF_FOLDER'])) {
            $arParams['SEF_FOLDER'] = '/admin/order/';
        }

        if (empty($arParams['SEF_URL_TEMPLATES'])) {
            $arParams['SEF_URL_TEMPLATES'] = array(
                'list' => '',
                'element' => '#ID#/',
            );
        }

        if (empty($arParams['SET_STATUS_404'])) {
            $arParams['SET_STATUS_404'] = 'Y';
        }

        if (empty($arParams['SHOW_404'])) {
            $arParams['SHOW_404'] = 'Y';
        }

        if (empty($arParams['CACHE_FILTER'])) {
            $arParams['CACHE_FILTER'] = 'Y';
        }

        if (empty($arParams['CACHE_GROUPS'])) {
            $arParams['CACHE_GROUPS'] = 'Y';
        }

        if (empty($arParams['CACHE_TIME'])) {
            $arParams['CACHE_TIME'] = 3600;
        }

        if (empty($arParams['CACHE_TYPE'])) {
            $arParams['CACHE_TYPE'] = 'A';
        }

        return $arParams;
    }

    /**
     * @throws LoaderException
     */
    public function executeComponent(): void
    {
        $this->checkModules();

        $defaultUrlTemplates404 = array(
            'list' => '',
            'element' => '#ID#/',
        );
        $defaultVariableAliases404 = array();
        $variables = array();

        $urlTemplates = CComponentEngine::MakeComponentUrlTemplates(
            $defaultUrlTemplates404,
            $this->arParams['SEF_URL_TEMPLATES']
        );

        $variableAliases = CComponentEngine::makeComponentVariableAliases(
            $defaultVariableAliases404,
            $this->arParams['SEF_URL_TEMPLATES']
        );

        $componentPage = (new CComponentEngine($this))->guessComponentPath(
            $this->arParams['SEF_FOLDER'],
            $urlTemplates,
            $variables
        );

        $is404 = false;
        if (!$componentPage) {
            $componentPage = 'list';
            $is404 = true;
        }

        if ($is404) {
            $folder404 = str_replace('\\', '/', $this->arParams['SEF_FOLDER']);

            if ($folder404 !== '/') {
                $folder404 = '/'.trim($folder404, '/ \t\n\r\0\x0B').'/';
            }

            if (substr($folder404, -1) === '/') {
                $folder404 .= 'index.php';
            }

            global $APPLICATION;
            if ($folder404 !== $APPLICATION->GetCurPage(true)) {
                Tools::process404(
                    '',
                    $this->arParams['SET_STATUS_404'] === 'Y',
                    $this->arParams['SET_STATUS_404'] === 'Y',
                    $this->arParams['SHOW_404'] === 'Y',
                    $this->arParams['FILE_404']
                );
            }
        }

        CComponentEngine::InitComponentVariables($componentPage, array(), $variableAliases, $variables);

        $this->arResult = array(
            'FOLDER' => $this->arParams['SEF_FOLDER'],
            'URL_TEMPLATES' => $urlTemplates,
            'VARIABLES' => $variables,
            'ALIASES' => $variableAliases,
        );

        $this->IncludeComponentTemplate($componentPage);
    }

    /**
     * @return void
     * @throws LoaderException
     * @throws Exception
     */
    private function checkModules(): void
    {
        if (!Loader::includeModule('sale')) {
            throw new \Exception('Не установлен модуль sale');
        }

        if (!Loader::includeModule('iblock')) {
            throw new \Exception('Не установлен модуль iblock');
        }
    }
}
