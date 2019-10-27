<?php
/**
 * @author  Hasan Karami
 * @version 1
 * @package CCore
 */
namespace Kernel\Http\Gate
{

    use Kernel\Http\Request;
    use Kernel\Database\Pecod;

    final class CrossSiteRequestForgery
    {
        /**
         * @param  Request $request
         * @return void
         */
        public final function run(Request $request)
        {

        }
    }
}