<?php

namespace TimurFlush\PhalconCaptcha\Controllers;

use Phalcon\Mvc\Controller;

/**
 * Class CaptchaController
 * @package TimurFlush\PhalconCaptcha\Controllers
 * @version 1.0.0
 * @author Timur Flush
 */
class CaptchaController extends Controller
{
    /**
     * Show the image on screen.
     *
     * @throws \Exception
     */
    public function getAction()
    {
        if ($this->captcha instanceof \TimurFlush\PhalconCaptcha\Adapter\Image) {
            $this->response->setContentType('image/png');
            $this->response->setHeader("Expires", "Wed, 1 Jan 1997 00:00:00 GMT");
            $this->response->setHeader("Last-Modified", gmdate("D, d M Y H:i:s") . " GMT");
            $this->response->setHeader("Cache-Control", "no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
            $this->response->setHeader("Pragma", "no-cache");
            $this->response->sendHeaders();

            $params = $this->dispatcher->getParams();

            do {
                if (!isset($params[0]))
                    break;

                $matches = [];
                if (!preg_match('/^(.+)-(.+)-(.+)$/', $params[0], $matches))
                    break;

                $this->setWidthHeightFontSize((int)$matches[1], (int)$matches[2], (int)$matches[3]);

                if (!isset($params[1]))
                    break;

                $matches = [];
                if (preg_match('/^(.+)-(.+)-(.+)-(.+)$/', $params[1], $matches)){
                    $this->setFontRGBA((int)$matches[1], (int)$matches[2], (int)$matches[3], (int)$matches[4]);
                }else if (preg_match('/^(.+)-(.+)-(.+)$/', $params[1], $matches)){
                    $this->setFontRGBA((int)$matches[1], (int)$matches[2], (int)$matches[3]);
                }else{
                    break;
                }

                if (!isset($params[2]))
                    break;

                $matches = [];
                if (preg_match('/^(.+)-(.+)-(.+)-(.+)$/', $params[2], $matches)){
                    $this->setBackgroundColor((int)$matches[1], (int)$matches[2], (int)$matches[3], (int)$matches[4]);
                }else if (preg_match('/^(.+)-(.+)-(.+)$/', $params[2], $matches)){
                    $this->setBackgroundColor((int)$matches[1], (int)$matches[2], (int)$matches[3]);
                }else{
                    break;
                }

            }while(false);

            $this->captcha->showImage();
        }
    }

    /**
     * @param int $width
     * @param int $height
     * @param int $fontSize
     */
    private function setWidthHeightFontSize(int $width, int $height, int $fontSize)
    {
        $this->captcha->setWidth($width);
        $this->captcha->setHeight($height);
        $this->captcha->setFontSize($fontSize);
    }

    /**
     * @param int $red
     * @param int $green
     * @param int $blue
     * @param int $alpha
     */
    private function setFontRGBA(int $red, int $green, int $blue, int $alpha = 0)
    {
        $this->captcha->setFontColor($red, $green, $blue, $alpha);
    }

    /**
     * @param int $red
     * @param int $green
     * @param int $blue
     * @param int $alpha
     */
    private function setBackgroundRGBA(int $red, int $green, int $blue, int $alpha = 0)
    {
        $this->captcha->setBackgroundColor($red, $green, $blue, $alpha);
    }
}