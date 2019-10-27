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

    final class SignInController extends Controller
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
            $this->view->render('SignInController.Index');
        }
        /**
         * @param  Request $request
         * @return void
         */
        public final function SignIn(Request $request)
        {

        }
    }
}