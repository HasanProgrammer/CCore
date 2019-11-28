<?php
/**
 * @author  Hasan Karami
 * @version 1
 * @package CCore
 */
namespace Libs\Finals
{
    final class Captcha
    {
        private const CAPTCHA_STR = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';

        private $imageWidth;
        private $imageHeight;
        private $imageFontSize;
        private $stringImageCaptcha;

        /**
         * @param  integer $length
         * @return void
         */
        public final function __construct(int $length)
        {
            $this->stringImageCaptcha = substr(str_shuffle(Captcha::CAPTCHA_STR), $length);
            Session::init();
            Session::set('CAPTCHA', $this->stringImageCaptcha);
        }
        /**
         * @param  integer $size
         * @return Captcha
         */
        public final function setImageFont(int $size) : Captcha
        {
            $this->imageFontSize = $size;
            return $this;
        }

        /**
         * @param  integer $width
         * @return Captcha
         */
        public final function setImageWidth(int $width) : Captcha
        {
            $this->imageWidth = $width;
            return $this;
        }
        /**
         * @param  integer $height
         * @return Captcha
         */
        public final function setImageHeight(int $height) : Captcha
        {
            $this->imageHeight = $height;
            return $this;
        }
        /**
         * @return void
         */
        public final function create()
        {
            header('Content-Type: image/jpeg');
            $image          = imagecreate($this->imageWidth, $this->imageHeight);
            imagecolorallocate($image, 255, 255, 255);
            //$imageTextColor = imagecolorallocate($image, 255, 255, 255);
            //imagettftext($image, $this->imageFontSize, 15, 30, $imageTextColor, 'Roboto-Regular.ttf', $this->stringImageCaptcha);
            imagejpeg($image);
        }
    }
}