<?php
/**
 * @author  Hasan Karami
 * @version 1
 * @package CCore
 */
namespace Kernel\Http
{

    use Kernel\Exceptions\MiddlewareException;

    final class Middleware
    {

        private $providers;

        /**
         * @return void
         */
        public final function __construct()
        {
            $this->providers = config('Provider')['Middleware'];
        }
        /**
         * @param  string $middleware
         * @return self
         */
        public final function handle(string $middleware) : self
        {
            try
            {
                if(isInAssoc($this->providers, $middleware))
                {
                    if(class_exists(isInAssoc($this->providers, $middleware, true)))
                    {
                        $reflection = new \ReflectionClass(isInAssoc($this->providers, $middleware, true));
                        if($reflection->hasMethod('run'))
                        {
                            if($reflection->getMethod('run')->getNumberOfParameters() == 1)
                                $reflection->getMethod('run')->invokeArgs($reflection->newInstance(), [new Request()]);
                            else throw new MiddlewareException('File: '.__FILE__.' --Line: '.__LINE__);
                            return $this;
                        }
                        else throw new MiddlewareException('File: '.__FILE__.' --Line: '.__LINE__);
                    } else throw new MiddlewareException('File: '.__FILE__.' --Line: '.__LINE__);
                } else throw new MiddlewareException('File: '.__FILE__.' --Line: '.__LINE__);
            }
            catch (MiddlewareException $middlewareException)
            {
                d( $middlewareException->getMessage() );
            }
            catch (\ReflectionException $e)
            {
                d( $e->getMessage() );
            }

            return $this;
        }
    }
}