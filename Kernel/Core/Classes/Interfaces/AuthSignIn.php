<?php

/**
 * @author  Hasan Karami
 * @version 1
 * @package CCore
 */
namespace Kernel\Core\Classes\Interfaces
{
    interface AuthSignIn
    {
        public function onSignInSuccess();
        public function onSignInError();
    }
}