<?php

/**
 * @author  Hasan Karami
 * @version 1
 * @package CCore
 */
namespace MVC\Controllers
{

    use Kernel\Http\Request;
    use Kernel\Http\Controller;

    class CartController extends Controller
    {
        /**
         * @return void
         */
        public function __construct()
        {
            parent::__construct();
        }

        /**
         * @param  Request $request
         * @return void
         */
        public function Index(Request $request)
        {
            $this->view->render('CartController.Index');
        }
    }
}