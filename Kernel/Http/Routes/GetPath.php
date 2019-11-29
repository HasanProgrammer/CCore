<?php
/**
 * @author  Hasan Karami
 * @version 1
 * @package CCore
 */
namespace Kernel\Http\Routes
{
    
    use Kernel\Http\Route\HttpRequest;
    
    final class GetPath
    {
        /**
         * @return void
         */
        public static function run()
        {
            HttpRequest::get("Error"         , config('Route')['Get']['Error']);
            HttpRequest::get("SignUp"        , config('Route')['Get']['Sign_Up']);
            HttpRequest::get("SignIn"        , config('Route')['Get']['Sign_In']);
            HttpRequest::get("User"          , config('Route')['Get']['User']);
            HttpRequest::get("Admin"         , config('Route')['Get']['Admin']);
            HttpRequest::get("Cart"          , config('Route')['Get']['Cart']);
            HttpRequest::get("Cart/Request"  , config('Route')['Get']['Cart/Request']);
            HttpRequest::get("Cart/Response" , config('Route')['Get']['Cart/Response']);
            HttpRequest::get("Repair"        , config('Route')['Get']['Repair']);
        }
    }
}