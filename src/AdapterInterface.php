<?php

namespace TimurFlush\PhalconCaptcha;

/**
 * Interface AdapterInterface
 * @package TimurFlush\PhalconCaptcha
 * @version 1.0.2
 * @author Timur Flush
 */
interface AdapterInterface
{
    /**
     * Check the captcha.
     *
     * @param string $value
     * @return bool
     */
    public function check(string $value, array $options = []) : bool;
}
