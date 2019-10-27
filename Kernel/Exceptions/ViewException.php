<?php
/**
 * @author  Hasan Karami
 * @version 1
 * @package CCore
 */
namespace Kernel\Exceptions
{

    use Throwable;

    final class ViewException extends \Exception
    {
        /**
         * @param string    $message
         * @param integer   $code
         * @param Throwable $previous
         */
        public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
        {
            parent::__construct($message, $code, $previous);
        }
    }
}