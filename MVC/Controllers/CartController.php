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

    final class CartController extends Controller
    {
        /**
         * @return void
         */
        public final function __construct()
        {
            parent::__construct();
        }
        /**
         * @param  Request $request
         * @return void
         */
        public final function Index(Request $request)
        {
            $this->view->render('CartController.Index');
        }
        /**
         * @param  Request $request
         * @return void
         */
        public final function Request(Request $request)
        {

        }
        /**
         * @param  Request $request
         * @return void
         */
        public final function Response(Request $request)
        {

        }
    }
}