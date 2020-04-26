<?php

use Libs\Finals\Session;
use Kernel\Http\Request;

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
        return "<img src=''/>";
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
        return "";
    }
}

if(!function_exists('request'))
{
    /**
     * @author  Hasan Karami
     * @version 1
     * @return  Request
     */
    function request() : Request
    {
        return new Request();
    }
}