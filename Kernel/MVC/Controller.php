<?php

/**
 * @author  Hasan Karami
 * @version 1
 * @package CCore
 */
namespace Kernel\MVC
{

    use Kernel\Http\Middleware;

    class Controller
    {
        /**
         * @var Middleware $middleware
         */
        protected Middleware $middleware;

        /**
         * @return void
         */
        public function __construct()
        {
            \Libs\Finals\Session::init();
            $this->middleware = new \Kernel\Http\Middleware();
            $this->middleware->handle("Boot");
        }
    }
}