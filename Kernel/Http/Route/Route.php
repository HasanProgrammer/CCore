<?php
/**
 * @author  Hasan Karami
 * @version 1
 * @package CCore
 */
namespace Kernel\Http\Route
{

    use Exception;
    use Kernel\MVC\View;
    use Kernel\MVC\Model;
    use Kernel\Http\Request;
    use Libs\Finals\Session;
    use Kernel\Http\Middleware;
    use Kernel\Exceptions\RouteException;
    use Kernel\Core\Classes\Interfaces\Http\Route as IRoute;

    final class Route
    {
        private $area;
        private $route;
        private $method;
        private $callable;
        private $throttle;
        private $parameter;
        private $controller;
        private $typeRequest;
        private $parameterRegex;
        private $middlewareRoute;
        /**
         * @param  string $route
         * @param  string | callable callable
         * @param  string $typeRequest
         * @return void
         */
        public final function __construct(string $route, $callable = null, string $typeRequest)
        {
            $this->route       = trim($route, '/');
            $this->callable    = $callable;
            $this->typeRequest = $typeRequest;
        }
        /**
         * @param  $stringURL
         * @return boolean
         */
        public final function match($stringURL) : bool
        {
            $BaseRoute = preg_replace_callback("#\[([\w]+)\]#", [$this , 'filterParameter'], $this->route);
            if(!preg_match("#^$BaseRoute$#i", $stringURL, $Matched))
                return false;
            unset($Matched[0]);
            $this->parameter[] = new Request();
            for($i = 0; $i < count(array_values($Matched)); $i++)
                array_push($this->parameter, array_values($Matched)[$i]);
            return true;
        }
        /**
         * @param  string $controller
         * @return Route
         */
        public final function controller(string $controller) : self
        {
            $this->controller = $controller."Controller";
            return $this;
        }
        /**
         * @param  string $method
         * @return Route
         */
        public final function action(string $method) : self
        {
            $this->method = $method;
            return $this;
        }
        /**
         * @param  string $parameter
         * @param  string $regex
         * @return Route
         */
        public final function filter(string $parameter, string $regex) : self
        {
            $this->parameterRegex[$parameter] = str_replace('(', '', str_replace(')', '', $regex));
            return $this;
        }
        /**
         * @param  string $area
         * @return Route
         */
        public final function area(string $area) : self
        {
            $this->area = $area;
            return $this;
        }
        /**
         * @param  string $middleware
         * @return Route
         */
        public final function middleware(string $middleware) : self
        {
            $this->middlewareRoute[$this->route][] = $middleware;
            return $this;
        }
        /**
         * @param  string   $order
         * @param  callable $callBack
         * @return Route
         */
        public final function fireWall(string $order, callable $callBack = null) : self
        {
            try
            {
                if(is_string($order))
                {
                    $partition = explode('|', $order);
                    if(count($partition) == 2)
                    {
                        if(preg_match('#Max:([0-9]+)#', $order, $matched))
                            $this->throttle['Limit'] = (integer)$matched[1];
                        else
                            throw new RouteException('File: '.__FILE__.' --Line: '.__LINE__);
                        if(preg_match('#Time:([0-9]+)([S|M|H])#', $order, $matched))
                        {
                            switch($matched[2])
                            {
                                case 'S':
                                    $this->throttle['Time'] = (integer)$matched[1];
                                    break;
                                case 'M':
                                    $this->throttle['Time'] = (integer)$matched[1]*60;
                                    break;
                                case 'H':
                                    $this->throttle['Time'] = (integer)$matched[1]*60*60;
                                    break;
                                case 'D':
                                    $this->throttle['Time'] = (integer)$matched[1]*24*60*60;
                            }
                        }
                        else throw new RouteException('File: '.__FILE__.' --Line: '.__LINE__);
                    } else throw new RouteException('File: '.__FILE__.' --Line: '.__LINE__);
                } else throw new RouteException('File: '.__FILE__.' --Line: '.__LINE__);
                $this->throttle['CallBack'] = $callBack;
                return $this;
            }
            catch (RouteException $routeException)
            {
                d( $routeException->getMessage() );
            }
        }
        /**
         * @return boolean
         */
        public final function hasFireWall() : bool
        {
            return isset($this->throttle) ? true : false;
        }
        /**
         * @return Route {
         */
        public final function callFireWall()
        {
            Session::init();
            if(!Session::checkExE(['Time'.$this->route , 'Max'.$this->route]))
            {
                Session::set('Time'.$this->route , time());
                Session::set('Max' .$this->route , 0);
            }
            $rateRequest = Session::get('Max'.$this->route);
            $rateRequest++;
            Session::set('Max'.$this->route , $rateRequest);
            if(Session::get('Max'.$this->route) >= $this->throttle['Limit'])
            {
                if(isset($this->throttle['CallBack']))
                {
                    call_user_func($this->throttle['CallBack'], $this);
                }
                else
                {
                    if(time() - Session::get('Time'.$this->route) < $this->throttle['Time'])
                    {
                        switch($this->typeRequest)
                        {
                            case 'GET':
                                redirect('Error');
                            break;
                        }
                    } else Session::delete(['Time'.$this->route , 'Max'.$this->route]);
                }
            }
            return $this;
        }
        /**
         * @return boolean
         */
        public final function hasMiddleware() : bool
        {
            return isset($this->middlewareRoute[$this->route]) ? true : false;
        }
        /**
         * @return Route
         */
        public final function callMiddleware() : Route
        {
            $gate = new Middleware();
            if(isset($this->middlewareRoute[$this->route]))
            {
                foreach($this->middlewareRoute[$this->route] as $middleware)
                    $gate->handle($middleware);
            }
            return $this;
        }
        /**
         * @return boolean
         */
        public final function hasArea() : bool
        {
            return isset($this->area) ? true : false;
        }
        /**
         * @return void
         */
        public final function call()
        {
            try
            {
                if(isset($this->callable))
                {
                    if(is_callable($this->callable))
                    {
                        call_user_func_array($this->callable, $this->parameter);
                    }
                    else if(is_string($this->callable))
                    {
                        $PartsCallable = explode('.', $this->callable);
                        if(count($PartsCallable) == 2)
                        {
                            if($this->hasArea())
                            {
                                if(file_exists('Areas/'.$this->area.'/Controllers/'.$PartsCallable[0].'.php'))
                                    include_once 'Areas/'.$this->area.'/Controllers/'.$PartsCallable[0].'.php';
                                else throw new RouteException('File: '.__FILE__.' --Line: '.__LINE__);
                                $reflection = new \ReflectionClass('Areas\\'.$this->area.'\\Controllers\\'.$PartsCallable[0]);
                                if(method_exists($reflection->newInstance(), $PartsCallable[1]))
                                    call_user_func_array([$reflection->newInstance(), $PartsCallable[1]], $this->parameter);
                                else throw new RouteException('File: '.__FILE__.' --Line: '.__LINE__);
                            }
                            else
                            {
                                if(file_exists('MVC/Controllers/'.$PartsCallable[0].'.php'))
                                    include_once 'MVC/Controllers/'.$PartsCallable[0].'.php';
                                else throw new RouteException('File: '.__FILE__.' --Line: '.__LINE__);
                                $reflection = new \ReflectionClass('\\MVC\\Controllers\\'.$PartsCallable[0]);
                                if(method_exists($reflection->newInstance(), $PartsCallable[1]))
                                    call_user_func_array([$reflection->newInstance(), $PartsCallable[1]], $this->parameter);
                                else throw new RouteException('File: '.__FILE__.' --Line: '.__LINE__);
                            }
                        }
                    }
                    else if($this->callable instanceof IRoute\Route)
                    {
                        $this->callable->doAnyThing($this->parameter, new Request());
                    }
                }
                else
                {
                    if($this->hasArea())
                    {
                        if(file_exists('Areas/'.$this->area.'/Controllers/'.$this->controller.'.php'))
                            include_once 'Areas/'.$this->area.'/Controllers/'.$this->controller.'.php';
                        else
                            throw new RouteException('File: '.__FILE__.' --Line: '.__LINE__);
                        $reflection = new \ReflectionClass('Areas\\'.$this->area.'\\Controllers\\'.$this->controller);
                        if(method_exists($reflection->newInstance(), $this->method))
                            call_user_func_array([$reflection->newInstance(), $this->method], $this->parameter);
                        else
                            throw new RouteException('File: '.__FILE__.' --Line: '.__LINE__);
                    }
                    else
                    {
                        if(file_exists('MVC/Controllers/'.$this->controller.'.php'))
                            include_once 'MVC/Controllers/'.$this->controller.'.php';
                        else
                            throw new RouteException('File: '.__FILE__.' --Line: '.__LINE__);
                        $reflection = new \ReflectionClass('MVC\\Controllers\\'.$this->controller);
                        if(method_exists($reflection->newInstance(), $this->method))
                            call_user_func_array([$reflection->newInstance(), $this->method], $this->parameter);
                        else
                            throw new RouteException('File: '.__FILE__.' --Line: '.__LINE__);
                    }
                }
            }
            catch (Exception $exception)
            {
                d( $exception->getMessage() );
            }
        }
        /**
         * @param  array $matched
         * @return string
         */
        private final function filterParameter(array $matched) : string
        {
            if(isset($this->parameterRegex[$matched[1]]))
                return '(' . $this->parameterRegex[$matched[1]] . ')';
            else
                return '([^/]+)';
        }
    }
}