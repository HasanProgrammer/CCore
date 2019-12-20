<?php

/**
 * @author  Hasan Karami
 * @version 1
 * @package CCore
 */
namespace Kernel\Http\Middleware
{

    use Libs\Finals\Session;
    use Kernel\Http\Request;
    use Kernel\Database\Pecod;

    class CrossSiteRequestForgery
    {
        /**
         * @param  Request $request
         * @return void
         */
        public function run(Request $request)
        {
            if(!$request->isJsonFile())
            {
                //Handle Get Request
                if( $request->isGetMethod() )
                {
                    if( $request->get()->has("csrf") )
                    {
                        if( $request->get()->csrf == Session::get("csrf") )
                        {

                        }
                    }
                }

                //Handle Post Request
                if( $request->isPostMethod() )
                {
                    if( $request->post()->has("csrf") )
                    {
                        if( $request->post()->csrf == Session::get("csrf") )
                        {

                        }
                    }
                }

                //Handle Put Request
                if( $request->isPutMethod() )
                {
                    if( $request->put()->has("csrf") )
                    {
                        if( $request->put()->csrf == Session::get("csrf") )
                        {

                        }
                    }
                }

                //Handle Patch Request
                if( $request->isPatchMethod() )
                {
                    if( $request->patch()->has("csrf") )
                    {
                        if( $request->patch()->csrf == Session::get("csrf") )
                        {

                        }
                    }
                }

                //Handle Delete Request
                if( $request->isDeleteMethod() )
                {
                    if( $request->delete()->has("csrf") )
                    {
                        if( $request->delete()->csrf == Session::get("csrf") )
                        {

                        }
                    }
                }
            }
        }
    }
}