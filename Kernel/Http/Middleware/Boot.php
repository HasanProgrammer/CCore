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

    class Boot
    {
        /**
         * @param  Request $request
         * @return void
         */
        public function run(Request $request)
        {
            //Set Session
            if( !Session::checkExE("csrf") ) Session::set("csrf" , hash("sha256" , time()));

            //Update Session
            Session::delete("csrf");
            Session::set("csrf" , hash("sha256" , time()));
        }
    }
}