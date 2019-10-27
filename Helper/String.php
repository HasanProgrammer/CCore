<?php

if(!function_exists('slug'))
{
    /**
     * @author  Hasan Karami
     * @version 1
     * @param   string $string
     * @return  string
     */
    function slug(string $string) : string
    {
        $string = str_replace(' ', '-', $string);
        return $string;
    }
}

if(!function_exists('immunization'))
{
    /**
     * @author  Hasan Karami
     * @version 1
     * @param   string $input
     * @return  string
     */
    function immunization(string $input) : string
    {
        return htmlentities(stripslashes($input));
    }
}