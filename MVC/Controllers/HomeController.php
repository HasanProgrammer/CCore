<?php
/**
 * @author  Hasan Karami
 * @version 1
 * @package CCore
 */
namespace MVC\Controllers
{

    use Kernel\Annotation;
    use Kernel\Http\Request;
    use Kernel\Http\Controller;
    use Kernel\Annotations\Route;

    /**
     * @Area()
     */
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
         * @param  Request $request
         * @return void
         */
        public final function Index(Request $request)
        {
            //(new Annotation(new Route()))->Translate();

            $this->view->render('HomeController.Index');
        }
    }
}