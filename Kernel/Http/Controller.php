<?php

/**
 * @author  Hasan Karami
 * @version 1
 * @package CCore
 */
namespace Kernel\Http
{

    use Kernel\MVC\View;
    use Kernel\MVC\Model;
    use Kernel\MVC\Controller as BaseController;

    class Controller extends BaseController
    {
        /**
         * @var View $view
         */
        protected View  $view;

        /**
         * @var Model $model
         */
        protected Model $model;

        /**
         * @return void
         */
        public function __construct()
        {
            parent::__construct();
            $this->view  = new View();
            $this->model = new Model();
        }
    }
}