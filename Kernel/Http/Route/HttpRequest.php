<?php
/**
 * @author  Hasan Karami
 * @version 1
 * @package CCore
 */
namespace Kernel\Http\Route
{

    use Kernel\Http\Request;
    use Kernel\Exceptions\RouteException;

    final class HttpRequest
    {
        private static $StringURL = null;
        private static $GERoute   = null;
        private static $PORoute   = null;
        private static $PURoute   = null;
        private static $PARoute   = null;
        private static $DERoute   = null;
        /**
         * @param  string $dirname
         * @return void
         */
        private final function __construct(string $dirname)
        {
            $result = explode(str_replace('\\', '/', $dirname), $_SERVER['REQUEST_URI']);
            if(count($result) == 2)
            {
                unset($result[0]);
                $result = array_values($result);
            }
            self::$StringURL = trim(explode('?', $result[0])[0], '/');
        }
        /**
         * @param  string $dirname
         * @return void
         */
        public final static function start(string $dirname)
        {
            new HttpRequest($dirname);
        }
        /**
         * @param  string $route
         * @param  string | callable $callable
         * @return Route
         */
        public final static function get(string $route, $callable = null) : Route
        {
            return self::$GERoute[] = new Route($route, $callable, 'GET');
        }
        /**
         * @param  string $route
         * @param  string | callable $callable
         * @return Route
         */
        public final static function post(string $route, $callable = null) : Route
        {
            return self::$PORoute[] = new Route($route, $callable, 'POST');
        }
        /**
         * @param  string $route
         * @param  string | callable $callable
         * @return Route
         */
        public final static function put(string $route, $callable = null) : Route
        {
            return self::$PURoute[] = new Route($route, $callable, 'PUT');
        }
        /**
         * @param  string $route
         * @param  string | callable $callable
         * @return Route
         */
        public final static function patch(string $route, $callable = null) : Route
        {
            return self::$PARoute[] = new Route($route, $callable, 'PATCH');
        }
        /**
         * @param  string $route
         * @param  string | callable $callable
         * @return Route
         */
        public final static function delete(string $route, $callable = null) : Route
        {
            return self::$DERoute[] = new Route($route, $callable, 'DELETE');
        }
        /**
         * @return null
         */
        public final static function exe()
        {
            try
            {
                $request = new Request();
                if($request->isDeleteMethod())
                    return self::callDeleteResponse();
                else if ($request->isPatchMethod())
                    return self::callPatchResponse();
                else if ($request->isPostMethod())
                    return self::callPostResponse();
                else if ($request->isPutMethod())
                    return self::callPutResponse();
                else if($request->isGetMethod())
                    return self::callGetResponse();
                else
                    redirect("Error");
                throw new RouteException('File: '.__FILE__.' --Line: '.__LINE__);
            }
            catch (RouteException $routeException)
            {
                d( $routeException->getMessage() );
            }
        }
        /**
         * @return mixed
         */
        private final static function callDeleteResponse()
        {
            foreach(self::$DERoute as $delete)
            {
                if($delete->match(self::$StringURL))
                {
                    if($delete->hasFireWall())
                        return $delete->callFireWall()->call();
                    else if($delete->hasGate())
                        return $delete->callGate()->call();
                    else if($delete->hasFireWall() && $delete->hasGate())
                        return $delete->callFireWall()->callGate()->call();
                    else
                        return $delete->call();
                }
            }
            header("HTTP/1.0 404 Not Found");
            redirect("Error");
        }
        /**
         * @return mixed
         */
        private final static function callGetResponse()
        {
            foreach(self::$GERoute as $get)
            {
                if($get->match(self::$StringURL))
                {
                    if($get->hasFireWall())
                        return $get->callFireWall()->call();
                    else if($get->hasMiddleware())
                        return $get->callMiddleware()->call();
                    else if($get->hasFireWall() && $get->hasMiddleware())
                        return $get->callFireWall()->callMiddleware()->call();
                    else
                        return $get->call();
                }
            }
            header("HTTP/1.0 404 Not Found");
            redirect("Error");
        }
        /**
         * @return mixed
         */
        private final static function callPatchResponse()
        {
            foreach(self::$PARoute as $patch)
            {
                if($patch->match(self::$StringURL))
                {
                    if($patch->hasFireWall())
                        return $patch->callFireWall()->call();
                    else if($patch->hasMiddleware())
                        return $patch->callMiddleware()->call();
                    else if($patch->hasFireWall() && $patch->hasMiddleware())
                        return $patch->callFireWall()->callMiddleware()->call();
                    else
                        return $patch->call();
                }
            }
            header("HTTP/1.0 404 Not Found");
            redirect("Error");
        }
        /**
         * @return mixed
         */
        private final static function callPostResponse()
        {
            foreach(self::$PORoute as $post)
            {
                if($post->match(self::$StringURL))
                {
                    if($post->hasFireWall())
                        return $post->callFireWall()->call();
                    else if($post->hasMiddleware())
                        return $post->callMiddleware()->call();
                    else if($post->hasFireWall() && $post->hasMiddleware())
                        return $post->callFireWall()->callMiddleware()->call();
                    else
                        return $post->call();
                }
            }
            header("HTTP/1.0 404 Not Found");
            redirect("Error");
        }
        /**
         * @return mixed
         */
        private final static function callPutResponse()
        {
            foreach(self::$PURoute as $put)
            {
                if($put->match(self::$StringURL))
                {
                    if($put->hasFireWall())
                        return $put->callFireWall()->call();
                    else if($put->hasMiddleware())
                        return $put->callMiddleware()->call();
                    else if($put->hasFireWall() && $put->hasMiddleware())
                        return $put->callFireWall()->callMiddleware()->call();
                    else
                        return $put->call();
                }
            }
            header("HTTP/1.0 404 Not Found");
            redirect("Error");
        }
    }
}