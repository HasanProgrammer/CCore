<?php

if(!function_exists('getViewException'))
{
    /**
     * @author  Hasan Karami
     * @version 1
     * @param   string $view
     * @param   string $parameter
     * @return  string
     */
    function getViewException(string $view, string $parameter) : string
    {
        $view = str_replace('.', '/', $view);
        ob_start();
        include_once 'MVC/Views/Exceptions/'.$view.'.php';
        return ob_get_clean();
    }
}

if(!function_exists('view'))
{
    /**
     * @author  Hasan Karami
     * @version 1
     * @return  \Kernel\MVC\View
     */
    function view() : \Kernel\MVC\View
    {
        return new \Kernel\MVC\View();
    }
}