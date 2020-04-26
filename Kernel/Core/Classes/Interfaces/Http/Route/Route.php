<?php

/**
 * @author  Hasan Karami
 * @version 1
 * @package CCore
 */
namespace Kernel\Core\Classes\Interfaces\Http\Route
{

    use Kernel\Http\Request;

    interface Route
    {
        /**
         * @param  array   $parameter
         * @param  Request $request
         * @return void
         */
        public function doAnyThing(array $parameter, Request $request) : void;
    }
}