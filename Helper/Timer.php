<?php

if(!function_exists('timer'))
{
    /**
     * @author  Hasan Karami
     * @version 1
     * @param   integer  $timer
     * @param   callable $callback
     * @return  mixed | boolean
     */
    function timer(int $timer, callable $callback) : ?bool
    {
        $nowTime = time();
        while(true)
        {
            if($nowTime + $timer < time())
            {
                return call_user_func($callback);
            }
        }

        return false;
    }
}