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

    final class HomeController extends Controller
    {
        /**
         * @return void
         */
        public final function __construct()
        {
            parent::__construct();
        }
        /**
         * @Method[Validate]
         * @param  Request $request
         * @return void
         */
        public final function Index(Request $request)
        {
            $this->view->render('HomeController.Index');
        }
    }
}