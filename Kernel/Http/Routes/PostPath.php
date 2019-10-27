<?php
/**
 * @author  Hasan Karami
 * @version 1
 * @package CCore
 */
namespace Kernel\Http\Routes
{

    use Kernel\Http\Route\HttpRequest;

    final class PostPath
    {
        /**
         * @return void
         */
        public static function run()
        {
            HttpRequest::post("Test"   , config('Route')['Post']['Test']);
            HttpRequest::post("SignUp" , config('Route')['Post']['Sign_Up']);
        }
    }
}