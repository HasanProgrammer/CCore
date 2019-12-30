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
            if( !$request->isJsonFile() )
            {
                if(
                    $request->isGetMethod()   ||
                    $request->isPostMethod()  ||
                    $request->isPutMethod()   ||
                    $request->isPatchMethod() ||
                    $request->isDeleteMethod()
                  )
                {
                    if(
                        $request->get()   ->has("csrf") ||
                        $request->post()  ->has("csrf") ||
                        $request->put()   ->has("csrf") ||
                        $request->patch() ->has("csrf") ||
                        $request->delete()->has("csrf")
                      )
                    {
                        if(
                            $request->get()   ->csrf != Session::get("csrf") ||
                            $request->post()  ->csrf != Session::get("csrf") ||
                            $request->put()   ->csrf != Session::get("csrf") ||
                            $request->patch() ->csrf != Session::get("csrf") ||
                            $request->delete()->csrf != Session::get("csrf")
                          )
                        {
                            redirect("Error");
                        }
                    }
                }
            }
        }
    }
}