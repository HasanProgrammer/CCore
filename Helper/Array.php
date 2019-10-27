<?php

if(!function_exists('isAssoc'))
{
    /**
     * @author  Hasan Karami
     * @version 1
     * @param   string | integer | array $array
     * @return  boolean
     */
    function isAssoc($array) : bool
    {
        if(is_string($array) || is_float($array) || is_integer($array) || is_bool($array) || is_null($array))
            return false;
        else
        {
            if(array() === $array)
                return false;
            return array_keys($array) !== range(0 , count($array) - 1);
        }
    }
}

if(!function_exists('isInAssoc'))
{
    /**
     * @author  Hasan Karami
     * @version 1
     * @param   array $array
     * @param   $keyItem
     * @param   boolean $valueReturn
     * @return  mixed
     */
    function isInAssoc(array $array, $keyItem, bool $valueReturn = false)
    {
        foreach($array as $item => $value)
        {
            if($item == $keyItem)
                return ($valueReturn == true ? $value : true);
            else if(isAssoc($value))
                isInAssoc($value, $keyItem, $valueReturn);
        }
        return ($valueReturn == true ? null : false);
    }
}