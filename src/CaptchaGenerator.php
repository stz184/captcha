<?php

namespace stz184\captcha;


class CaptchaGenerator
{
    protected $width;
    protected $height;
    protected $code;

    /**
     * @param integer $width Image width
     * @param integer $height Image height
     * @param string $code Code to display on the image
     */
    public function __construct($width = 146, $height = 30, $code = null)
    {
        $this->width    = $width;
        $this->height   = $height;
        $this->code     = $code ? $code : mb_substr(uniqid(mt_rand(), true), 0, 6);
    }

    /**
     * Get code showed on the image
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Output image to the browser
     * @return void
     */
    public function getImage()
    {
        header("Content-type: image/png");

        $font =  __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'fonts' .  DIRECTORY_SEPARATOR . 'offshore.ttf';

        $im = @imagecreate($this->width, $this->height) or die("Cannot Initialize new GD image stream");

        imagecolorallocate($im, 255, 250, 255);
        $noise_color = imagecolorallocate($im, 207, 239, 250);
        for ($i = 0; $i < ($this->width * $this->height) / 3; $i++) {
            imagefilledellipse($im, mt_rand(0, $this->width), mt_rand(0, $this->height), 1, 1, $noise_color);
        }

        /* generate random lines in background */
        for ($i = 0; $i < ($this->width * $this->height) / 150; $i++) {
            imageline($im, mt_rand(0, $this->width), mt_rand(0, $this->height), mt_rand(0, $this->width), mt_rand(0, $this->height), $noise_color);
        }

        $text_color[0] = imagecolorallocate($im, 255, 0, 0);
        $text_color[1] = imagecolorallocate($im, 51, 166, 207);

        for ($j = 0; $j < mb_strlen($this->code); $j++) {
            imagettftext($im, 20, 0, 5 + ($j * 23), 24, $text_color[$j % 2], $font, $this->code[$j]);
        }

        imagepng($im);
        imagedestroy($im);
    }
}