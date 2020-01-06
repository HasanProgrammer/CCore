<?php
use Kernel\MVC\View;

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
     * @return  View
     */
    function view() : View
    {
        return new View();
    }
}