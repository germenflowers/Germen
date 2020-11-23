<?php

namespace Germen;

/**
 * Class Tools
 * @package Germen
 */
class Tools
{
    /**
     * Tools constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param int $cacheTime
     * @param string $cacheId
     * @param $callback
     * @param mixed $callbackParams
     * @return mixed
     */
    public static function returnResultCache(int $cacheTime, string $cacheId, $callback, $callbackParams = '')
    {
        $cache = new \CPHPCache();

        if ($cache->InitCache($cacheTime, $cacheId, '/')) {
            $vars = $cache->GetVars();
            $result = $vars['result'];
        } elseif ($cache->StartDataCache()) {
            $result = $callback($callbackParams);
            $cache->EndDataCache(array('result' => $result));
        } else {
            $result = $callback($callbackParams);
        }

        return $result;
    }

}
