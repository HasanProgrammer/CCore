<?php

/**
 * @author  Hasan Karami
 * @version 1
 * @package CCore
 */
namespace Kernel
{
    class Cash
    {
        /**
         * @param  integer  $time
         * @param  callable $function
         * @return void
         */
        public static function handle(int $time, callable $function) : void
        {
            $current_url = ( isset($_SERVER['HTTPS']) ? "https://" : "http://" ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . ( isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : null );
            $cash_file   = "Storage/Cashe/".md5( $current_url ).".cashe";
            if(file_exists($cash_file) && time() - $time < filemtime($cash_file)) //Read Cashe's File
            {
                echo file_get_contents($cash_file);
                exit();
            }
            //Set Cashe's File
            ob_start("ob_gzhandler");
            call_user_func($function);
            $result = ob_get_clean();
            file_put_contents($cash_file , $result);
        }
    }
}