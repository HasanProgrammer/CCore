<?php

/**
 * @author  Hasan Karami
 * @version 1
 * @package CCore
 */
namespace MVC\Controllers
{

    use Kernel\Cash;
    use Kernel\Http\WebServices\WebService;
    use Libs\Finals\Session;
    use Kernel\Http\Request;
    use Kernel\Http\Controller;

    class HomeController extends Controller
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
        public function Index(Request $request) : void
        {
            $this->view->render('HomeController.Index');
        }
    }
}