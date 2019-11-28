<?php
/**
 * @author  Hasan Karami
 * @version 1
 * @package CCore
 */
namespace MVC\Controllers
{

    use Kernel\Http\Request;

    final class HasanController extends \Kernel\Http\Controller
    {
        /**
         * @return void
         */
        public final function __construct()
        {
            parent::__construct(); $this->middleware->handle('Boot');
        }
        /**
         * @param  Request $request
         * @return void
         */
        public final function Index(Request $request)
        {

        }
    }
}