<?php

namespace TimurFlush\PhalconCaptcha\Routes;

use Phalcon\Mvc\Router\Group;

/**
 * Class Captcha
 * @package TimurFlush\PhalconCaptcha\Routes
 * @version 1.0.0
 * @author Timur Flush
 */
class Captcha extends Group
{
    public function initialize()
    {
        $this->setPaths([
            'namespace' => 'TimurFlush\PhalconCaptcha\Controllers'
        ]);
        $this->add('/captcha/:params', [
            'controller' => 'Captcha',
            'action' => 'get',
            'params' => 1
        ])->setName('captcha');
    }
}