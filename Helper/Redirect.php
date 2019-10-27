<?php

if(!function_exists('redirect'))
{
    /**
     * @author  Hasan Karami
     * @version 1
     * @param   string $path
     * @return  void
     */
    function redirect(string $path)
    {
        //$path = str_replace('.', '/', $path);
        header("Location: ".root().$path);
        exit();
    }
}

if(!function_exists('redirectOnTime'))
{
    /**
     * @author  Hasan Karami
     * @version 1
     * @param   string  $path
     * @param   integer $time
     * @return  void
     */
    function redirectOnTime(string $path, int $time)
    {
        $path = str_replace('.', '/', $path);
        header("Refresh:".$time.";URL=".root().$path);
        exit();
    }
}

if(!function_exists('redirectToOtherUrl'))
{
    /**
     * @author  Hasan Karami
     * @version 1
     * @param   string $path
     * @return  void
     */
    function redirectToOtherUrl(string $path)
    {
        header("Location: ".$path);
    }
}

if(!function_exists('redirectOnTimeToOtherUrl'))
{
    /**
     * @author  Hasan Karami
     * @version 1
     * @param   string  $path
     * @param   integer $time
     * @return  void
     */
    function redirectOnTimeToOtherUrl(string $path, int $time)
    {
        header("Refresh:".$time.";URL=".$path);
    }
}