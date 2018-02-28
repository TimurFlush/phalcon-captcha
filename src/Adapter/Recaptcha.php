<?php

namespace TimurFlush\PhalconCaptcha\Adapter;

use Phalcon\Config;
use TimurFlush\PhalconCaptcha\Adapter;
use TimurFlush\PhalconCaptcha\AdapterInterface;

/**
 * Class Recaptcha
 * @package TimurFlush\PhalconCaptcha\Adapter
 * @version 1.0.0
 * @author Timur Flush
 */
class Recaptcha extends Adapter implements AdapterInterface
{
    /**
     * Recaptcha constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $config = $this->getConfig();
        if (!isset($config['publicKey'], $config['privateKey']))
            trigger_error("Public and/or private key is not passed.", E_USER_ERROR);
    }

    /**
     * Check the correct of captcha.
     *
     * @param string $value
     * @param array $options
     * @return bool
     */
    public function check(string $value, array $options = []): bool
    {
        $ch = curl_init('https://www.google.com/recaptcha/api/siteverify');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT => 5,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => [
                'secret' => $this->getConfig()['privateKey'],
                'response' => $value,
                'remoteip' => $options['ip']
            ]
        ]);
        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false)
            return false;

        $response = json_decode($response);
        return $response->success;
    }
}