<?php

if(!function_exists('dd'))
{
    /**
     * @author  Hasan Karami
     * @version 1
     * @param   array $array
     * @return  void
     */
    function dd(array $array)
    {
        echo '<pre>';
        print_r($array);
        echo '</pre>';
    }
}