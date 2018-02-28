<?php

return new \Phalcon\Config([
    'callbackMask' => 'TimurFlushPhalconCaptchaCallback_%count%',
    'callbackRegex' => '/TimurFlushPhalconCaptchaCallback_(.*?)/',

    'widgetIdMask' => 'TimurFlushPhalconCaptcha_%id%',
]);