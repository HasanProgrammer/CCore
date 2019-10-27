<?php
/**
 * @author  Hasan Karami
 * @version 1
 * @package CCore
 */
namespace Kernel\MVC
{
    class Controller
    {

        protected $middleware;

        /**
         * @return void
         */
        protected function __construct()
        {
            \Libs\Finals\Session::init();
            $this->middleware = new \Kernel\Http\Middleware();
        }
    }
}