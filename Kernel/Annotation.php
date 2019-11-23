<?php

namespace Kernel
{

    use ReflectionClass;
    use ReflectionException;
    use Kernel\Core\Classes\Interfaces\Annotation as IAnnotation;

    final class Annotation
    {
        private $reflection = null;

        /**
         * @param IAnnotation $annotation
         */
        public function __construct(IAnnotation $annotation)
        {
            try
            {
                $this->reflection = new ReflectionClass($annotation);
            }
            catch(ReflectionException $exception)
            {
                dd($exception->getMessage());
            }
        }

        public function Initial()
        {

        }
    }
}