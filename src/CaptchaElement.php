<?php

namespace TimurFlush\PhalconCaptcha;

use Phalcon\Forms\Element;
use Phalcon\Forms\ElementInterface;

/**
 * Class CaptchaElement
 * @package TimurFlush\PhalconCaptcha
 * @version 1.0.3
 * @author Timur Flush
 */
class CaptchaElement extends Element implements ElementInterface
{
    /**
     * @var int
     */
    private static $counter = 0;

    /**
     * Captcha render.
     *
     * @param null $attributes
     * @return string
     */
    public function render($attributes = null)
    {
        $di = \Phalcon\Di::getDefault();
        $service = $di->getShared('captcha');

        if (!($service instanceof AdapterInterface))
            trigger_error("Service 'captcha' did not return an object interface \TimurFlush\PhalconCaptcha\AdapterInterface", E_USER_ERROR);

        if ($service->getAdapterName() === 'Image') {
            return \Phalcon\Tag::textField($this->prepareAttributes($attributes));
        } else if ($service->getAdapterName() === 'Recaptcha'){
            $recaptchaConfig = Config::load('Recaptcha');

            $callbackName = str_replace('%count%', $this->getCounter(), $recaptchaConfig->callbackMask);
            $widgetIdMask = str_replace('%id%', $this->getCounter(), $recaptchaConfig->widgetIdMask);

            $adapterConfig = $service->getConfig();

            $widgetSettings = [];
            foreach(array_merge($adapterConfig, $attributes ?? []) as $attribute => $value)
                if (in_array($attribute, ['theme', 'type', 'size', 'tabindex', 'callback', 'expired-callback', 'error-callback']))
                    $widgetSettings[$attribute] = $value;

            $widgetSettingsJson = json_encode(
                array_merge(
                    $widgetSettings, [
                        'sitekey' => $adapterConfig['publicKey']
                    ]
                )
            );

            $html = <<<HTML
<script src="https://www.google.com/recaptcha/api.js?onload={$callbackName}&render=explicit" async defer></script>
<div id="{$widgetIdMask}"></div>
<script>
    var {$callbackName} = function(){
        grecaptcha.render('{$widgetIdMask}', {$widgetSettingsJson});
    };
</script>
HTML;
            $this->incrementCounter();
            return $html;
        }
    }

    /**
     * Return the counter.
     *
     * @return int
     */
    private function getCounter()
    {
        return self::$counter;
    }

    /**
     * Increment the counter.
     *
     * @return void
     */
    private function incrementCounter()
    {
        self::$counter++;
    }

}