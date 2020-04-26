<?php

/**
 * @author  Hasan Karami
 * @version 1
 * @package CCore
 */
namespace Kernel\Http\Middleware
{

    use Kernel\Http\Request;
    use Libs\Finals\Session;
    use Kernel\Database\Pecod;

    class Auth
    {
        /**
         * @param  Request $request
         * @return void
         */
        public function run(Request $request) : void
        {
            if(!Session::checkExE('Login')) redirect('SignIn');
        }
    }
}