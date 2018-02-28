<?php

namespace TimurFlush\PhalconCaptcha;

use Phalcon\Di\Injectable;

/**
 * Class Adapter
 * @package TimurFlush\PhalconCaptcha
 * @version 1.0.1
 * @author Timur Flush
 */
abstract class Adapter extends Injectable
{
    /**
     * @var \Phalcon\Config
     */
    private $_config = [];

    /**
     * Adapter constructor.
     * @param Config|null $config
     */
    public function __construct(array $config = [])
    {
        $this->setConfig($config);
    }

    /**
     * Return the config.
     *
     * @return array
     */
    public function getConfig() : array
    {
        return $this->_config;
    }

    /**
     * Set the config.
     *
     * @param array $config
     * @return void
     */
    public function setConfig(array $config) : void
    {
        $this->_config = $config;
    }


    /**
     * Return the captcha's adapter name.
     *
     * @return string
     */
    public final function getAdapterName()
    {
        $explode = explode("\\", get_class($this));
        return end($explode);
    }


    /**
     * @return CaptchaElement
     */
    public final function getElement()
    {
        return new CaptchaElement('captcha');
    }
}