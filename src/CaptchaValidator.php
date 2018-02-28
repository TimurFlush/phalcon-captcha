<?php

namespace TimurFlush\PhalconCaptcha;

use Phalcon\Validation;
use Phalcon\Validation\Message;
use Phalcon\Validation\Validator;
use TimurFlush\PhalconCaptcha\Adapter\Image as ImageAdapter;
use TimurFlush\PhalconCaptcha\Adapter\Recaptcha as RecaptchaAdapter;

/**
 * Class CaptchaValidator
 * @package TimurFlush\PhalconCaptcha
 * @version 1.0.0
 * @author Timur Flush
 */
class CaptchaValidator extends Validator implements Validation\ValidatorInterface
{
    public function validate(\Phalcon\Validation $validation, $attribute)
    {
        $this->setOption('cancelOnFail', true);
        $captcha = $validation->getDI()->getShared('captcha');

        if (!($captcha instanceof AdapterInterface))
            trigger_error("Service 'captcha' did not return an object interface \TimurFlush\PhalconCaptcha\AdapterInterface", E_USER_ERROR);

        if ($captcha instanceof ImageAdapter){
            $value = $validation->getValue($attribute);

            $validate = $captcha->check($value);
            if ($validate === false){

                $label = $this->prepareLabel($validation, $attribute);
                $message = $this->prepareMessage($validation, $attribute, 'Captcha');
                $code = $this->prepareCode($attribute);

                $replacePairs = [':field' => $label];

                $validation->appendMessage(
                    new Message(
                        strtr($message, $replacePairs),
                        $attribute,
                        'Captcha',
                        $code
                    )
                );
                return false;
            }

            return true;
        }else if ($captcha instanceof RecaptchaAdapter){
            $recaptchaConfig = Config::load('Recaptcha');

            $validate = $captcha->check(
                $validation->request->getPost('g-recaptcha-response'),
                ['ip' => $validation->request->getClientAddress()]
            );
            if ($validate !== true){

                $label = $this->prepareLabel($validation, $attribute);
                $message = $this->prepareMessage($validation, $attribute, 'Captcha');
                $code = $this->prepareCode($attribute);

                $replacePairs = [':field' => $label];

                $validation->appendMessage(
                    new Message(
                        strtr($message, $replacePairs),
                        $attribute,
                        'Captcha',
                        $code
                    )
                );
                return false;
            }

            return true;
        }else{
            trigger_error("Service 'captcha' does not map to current adapters.", E_USER_ERROR);
        }
    }
}