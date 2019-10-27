<?php
/**
 * @author  HasanProgrammer
 * @version 1
 * @package CCore
 */
namespace Kernel\Core\Classes\Interfaces\Http\Route
{
    interface Route
    {
        /**
         * @param  array $parameter
         * @param  \Kernel\Http\Request $request
         * @return void
         */
        public function doAnyThing(array $parameter, \Kernel\Http\Request $request);
    }
}