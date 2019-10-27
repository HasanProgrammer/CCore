<?php

use Libs\Finals\Session;

if(!function_exists('getHttpField'))
{
    /**
     * @author  Hasan Karami
     * @version 1
     * @param   string $httpMethod
     * @return  string
     */
    function getHttpField(string $httpMethod) : string
    {
        if($httpMethod == 'POST')
            return "<input type='hidden' name='$httpMethod' value='__POST'/>";
        else if($httpMethod == "PUT")
            return "<input type='hidden' name='$httpMethod' value='__PUT'/>";
        else if($httpMethod == "PATCH")
            return "<input type='hidden' name='$httpMethod' value='__PATCH'/>";
        else if($httpMethod == "DELETE")
            return "<input type='hidden' name='$httpMethod' value='__DELETE'/>";
        return "";
    }
}

if(!function_exists('getCaptchaFields'))
{
    /**
     * @author  Hasan Karami
     * @version 1
     * @return  string
     */
    function getCaptchaFields() : string
    {
        return "<img src='".root()."UI/Images/PHP/Captcha/1996.php'>";
    }
}

if(!function_exists('root'))
{
    /**
     * @author  Hasan Karami
     * @version 1
     * @return  string
     */
    function root(): string
    {
        return isset($_SERVER['HTTPS']) ? "https://" : "http://" . $_SERVER['HTTP_HOST'] . "/";
    }
}

if(!function_exists('bankPortResponse'))
{
    /**
     * @author  Hasan Karami
     * @version 1
     * @return  string
     */
    function getBankPortResponseUrl() : string
    {
        return "http://0f5b7245.ngrok.io/EasyPHP.V1/Cart/Response?Amount=800";
    }
}

if(!function_exists('request'))
{
    /**
     * @author  Hasan Karami
     * @version 1
     * @return  \Kernel\Http\Request
     */
    function request() : \Kernel\Http\Request
    {
        return new \Kernel\Http\Request();
    }
}