<?php
/**
 * @author  Hasan Karami
 * @version 1
 * @package CCore
 */
namespace Kernel\Http\Gate
{

    use Kernel\Http\Request;
    use Libs\Finals\Session;
    use Kernel\Database\Pecod;

    final class Auth
    {
        /**
         * @param  Request $request
         * @return void
         */
        public final function run(Request $request)
        {
            if(!Session::checkExE('Login')) redirect('SignIn');
        }
    }
}