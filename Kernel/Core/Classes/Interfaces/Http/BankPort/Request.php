<?php

/**
 * @author  Hasan Karami
 * @version 1
 * @package CCore
 */
namespace Kernel\Core\Classes\Interfaces\Http\BankPort
{
    interface Request
    {

        /**
         * @param  string  $authority
         * @param  integer $status
         * @return void
         */
        public function onSuccessRespons(string $authority, int $status) : void;

        /**
         * @param  string  $authority
         * @param  integer $status
         * @return void
         */
        public function onErrorRespons(string $authority, int $status) : void;
    }
}