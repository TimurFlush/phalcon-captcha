<?php

namespace TimurFlush\PhalconCaptcha;

/**
 * Class Config
 * @package TimurFlush\PhalconCaptcha
 * @version 1.0.0
 * @author Timur Flush
 */
class Config
{
    /**
     * Return the loaded config.
     *
     * @param string $configName
     * @return \Phalcon\Config
     */
    public static function load(string $configName) : \Phalcon\Config
    {
        return require __DIR__ . '/Config/' . $configName . '.php';
    }
}