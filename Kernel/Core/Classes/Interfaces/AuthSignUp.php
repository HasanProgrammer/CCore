<?php

/**
 * @author  Hasan Karami
 * @version 1
 * @package CCore
 */
namespace Kernel\Core\Classes\Interfaces
{
    interface AuthSignUp
    {

        /**
         * @param string $message
         * @param array  $info
         */
        public function onSignUpSuccess(string $message, array $info) : void;

        /**
         * @param string $message
         */
        public function onSignUpError(string $message) : void;
    }
}