<?php
/**
 * @author  Hasan Karami
 * @version 1
 * @package CCore
 */
namespace Kernel\Http
{

    use Kernel\MVC\Controller as BaseController;

    class Controller extends BaseController
    {

        protected $view;
        protected $model;

        /**
         * @return void
         */
        public function __construct()
        {
            parent::__construct();
            $this->view  = new \Kernel\MVC\View();
            $this->model = new \Kernel\MVC\Model();
        }
    }
}