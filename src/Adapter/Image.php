<?php

namespace TimurFlush\PhalconCaptcha\Adapter;

use TimurFlush\PhalconCaptcha\Adapter;
use TimurFlush\PhalconCaptcha\AdapterInterface;

/**
 * Class Drawer
 * @package TimurFlush\PhalconCaptcha
 * @version 1.0.1
 * @author Timur Flush
 */
class Image extends Adapter implements AdapterInterface
{
    /**
     * @var array
     */
    private $_options = [
        'bg' => [
            'width' => 150,
            'height' => 40,
            'color' => [
                'R' => 0,
                'G' => 0,
                'B' => 0,
                'A' => 127
            ]
        ],
        'font' => [
            'color' => [
                'R' => 0,
                'G' => 0,
                'B' => 0,
                'A' => 0,
            ],
            'randomAngle' => false,
            'length' => 4,
            'size' => 15,
            'allowNumbers' => true,
            'allowLetters' => false,
            'numbers' => '0123456789',
            'letters' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
        ]
    ];

    /**
     * Image constructor.
     */
    public function __construct()
    {
        if (!$this->session->has('captcha'))
            $this->resetCaptcha();
    }

    /**
     * Check the captcha.
     *
     * @param string $value
     * @param array $options
     * @throws \Phalcon\Security\Exception
     * @return bool
     */
    public function check(string $value, array $options = []) : bool
    {
        if (strtolower($value) === strtolower($this->session->get('captcha'))) {
            $this->resetCaptcha();
            return true;
        }
        $this->resetCaptcha();
        return false;
    }

    /**
     * Return the html image tag.
     *
     * Example:
     * $captcha = new \TimurFlush\PhalconCaptcha\Adapter\Image();
     *
     * //Returns "<img src='/captcha' style='display:inline;'>"
     * echo $captcha->getImage(['style' => 'display:inline;']);
     *
     * @param array $parameters
     * @return string
     */
    public function getImage(array $parameters = [])
    {
        $url = '';
        if (isset($parameters['width'], $parameters['height'], $parameters['font_size'])){

            $url .= join('-', [
                $parameters['width'],
                $parameters['height'],
                $parameters['font_size']
            ]);

            if (isset($parameters['font']['R'], $parameters['font']['G'], $parameters['font']['B'])){
                $url .= '/'
                    . join('-', [
                        $parameters['font']['R'],
                        $parameters['font']['G'],
                        $parameters['font']['B']
                    ])
                    . (
                    ( isset($parameters['font']['A']) )
                        ? '-' . $parameters['font']['A']
                        : ''
                    );
                if (isset($parameters['bg']['R'], $parameters['bg']['G'], $parameters['bg']['B'])){
                    $url .=
                        '/'
                        . join('-', [
                            $parameters['bg']['R'],
                            $parameters['bg']['G'],
                            $parameters['bg']['B']
                        ])
                        . (
                        ( isset($parameters['bg']['A']) )
                            ? '-' . $parameters['bg']['A']
                            : ''
                        );
                }
            }
        }

        return \Phalcon\Tag::image(
            array_merge(
                [
                    'src' => $this->url->get([
                        'for' => 'captcha',
                        'params' => (string)$url
                    ])
                ]
            )
        );
    }

    /**
     * Reset the captcha.
     *
     * @throws \Phalcon\Security\Exception
     * @return void
     */
    private function resetCaptcha() : void
    {
        $this->session->set('captcha', $this->security->getRandom()->hex(12));
    }

    /**
     * Set the background width.
     *
     * @param int $width Pixels.
     * @return void
     */
    public function setWidth(int $width) : void
    {
        if ($width > 0 && $width < 999)
            $this->_options['bg']['width'] = $width;
    }

    /**
     * Return the background width.
     *
     * @return mixed
     */
    public function getWidth() : int
    {
        return $this->_options['bg']['width'];
    }

    /**
     * Set the background height.
     *
     * @param int $height Pixels.
     * @return void
     */
    public function setHeight(int $height) : void
    {
        if ($height > 0 && $height < 999)
            $this->_options['bg']['height'] = $height;
    }

    /**
     * Return the background height.
     *
     * @return mixed
     */
    public function getHeight() : int
    {
        return $this->_options['bg']['height'];
    }

    /**
     * Set the background color.
     *
     * @param int $red Pixels.
     * @param int $green Pixels.
     * @param int $blue Pixels.
     * @param int $alpha Alpha-channel.
     * @return void
     */
    public function setBackgroundColor(int $red = 255, int $green = 255, int $blue = 255, int $alpha = 0) : void
    {
        if ($red > 255 || $red < 0)
            return;
        if ($green > 255 || $green < 0)
            return;
        if ($blue > 255 || $blue < 0)
            return;
        if ($alpha > 127 || $alpha < 0)
            return;

        $this->_options['bg']['color'] = [
            'R' => $red,
            'G' => $green,
            'B' => $blue,
            'A' => $alpha
        ];
    }

    /**
     * Return the background color.
     *
     * @return array
     */
    public function getBackgroundColor() : array
    {
        return $this->_options['bg']['color'];
    }

    /**
     * Set the font size.
     *
     * @param int $size
     * @return void
     */
    public function setFontSize(int $size = 19) : void
    {
        if ($size <= 0)
            return;

        $this->_options['font']['size'] = $size;
    }

    /**
     * Return the font size.
     *
     * @return int
     */
    public function getFontSize() : int
    {
        return $this->_options['font']['size'];
    }

    /**
     * Set the allow numbers.
     *
     * @param bool $allow
     * @return void
     */
    public function setAllowNumbers(bool $allow = true) : void
    {
        $this->_options['font']['allowNumbers'] = $allow;
    }

    /**
     * Return the allow on using numbers.
     *
     * @return mixed
     */
    public function getAllowNumbers()
    {
        return $this->_options['font']['allowNumbers'];
    }

    /**
     * Set the allow letters.
     *
     * @param bool $allow
     * @return void
     */
    public function setAllowLetters(bool $allow = true) : void
    {
        $this->_options['font']['allowLetters'] = $allow;
    }

    /**
     * Return the allow on using letters;
     *
     * @return mixed
     */
    public function getAllowLetters()
    {
        return $this->_options['font']['allowLetters'];
    }

    /**
     * Set the font color.
     *
     * @param int $red Pixels.
     * @param int $green Pixels.
     * @param int $blue Pixels.
     * @param int $alpha Alpha-channel.
     * @return void
     */
    public function setFontColor(int $red = 0, int $green = 0, int $blue = 0, int $alpha = 255) : void
    {
        if ($red > 255 || $red < 0)
            return;
        if ($green > 255 || $green < 0)
            return;
        if ($blue > 255 || $blue < 0)
            return;
        if ($alpha > 127 || $alpha < 0)
            return;

        $this->_options['font']['color'] = [
            'R' => $red,
            'G' => $green,
            'B' => $blue,
            'A' => $alpha
        ];
    }

    /**
     * Return the font color.
     *
     * @return array
     */
    public function getFontColor() : array
    {
        return $this->_options['font']['color'];
    }

    /**
     * Set the length.
     *
     * @param int $length
     * @return void
     */
    public function setLength(int $length) : void
    {
        if ($length <= 0)
            return;

        $this->_options['font']['length'] = $length;
    }

    /**
     * Return the length.
     *
     * @return int Length.
     */
    public function getLength() : int
    {
        return $this->_options['font']['length'];
    }

    /**
     * Set the angle.
     *
     * @param bool $angle
     * @return void
     */
    public function setRandomAngle(bool $angle) : void
    {
        $this->_options['font']['randomAngle'] = $angle;
    }

    /**
     * Return the angle.
     *
     * @return bool
     */
    public function getRandomAngle() : bool
    {
        return $this->_options['font']['randomAngle'];
    }

    /**
     * @throws \Exception
     */
    public function showImage()
    {
        $im = imagecreatetruecolor($this->getWidth(), $this->getHeight());
        imagesavealpha($im, true);

        $bgColor = $this->getBackgroundColor();

        $fontPath = dirname(dirname(__DIR__)) . '/resources/font.ttf';

        if (!file_exists($fontPath))
            trigger_error("Font file is not exists.", E_USER_ERROR);

        $bg = imagecolorallocatealpha($im, $bgColor['R'], $bgColor['G'], $bgColor['B'], $bgColor['A']);
        imagefill($im, 0, 0, $bg);

        $text = '';
        foreach(range(1, $this->getLength()) as $position){
            $numberOrLetter = random_int(0, 1);
            if (($numberOrLetter === 0 && $this->getAllowNumbers()) || !$this->getAllowLetters()){
                $numberLength = mb_strlen($this->_options['font']['numbers']) - 1;
                $letter = $this->_options['font']['numbers']{random_int(0, $numberLength)};
            }else if (($numberOrLetter === 1 && $this->getAllowLetters()) || !$this->getAllowNumbers()){
                $lettersLength = mb_strlen($this->_options['font']['letters']) - 1;
                $letter = $this->_options['font']['letters']{random_int(0, $lettersLength)};
            }

            $text .= $letter;

            $angle = 0;
            if ($this->getRandomAngle())
                $angle = random_int(-25, 25);

            $fontColor = $this->getFontColor();

            $x = ($this->getWidth() - $this->getFontSize()) / $this->getLength() * $position - $this->getFontSize();
            $y = $this->getHeight() - ( ($this->getHeight() - $this->getFontSize()) / 2 );
            $color = imagecolorallocate($im, $fontColor['R'], $fontColor['G'], $fontColor['B'] );
            imagettftext($im, $this->getFontSize(), $angle, $x, $y, $color, $fontPath, $letter);
        }

        $this->session->set('captcha', strtolower($text));

        imagepng($im);
        imagedestroy($im);
    }
}