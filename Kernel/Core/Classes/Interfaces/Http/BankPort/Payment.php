<?php

/**
 * @author  Hasan Karami
 * @version 1
 * @package CCore
 */
namespace Kernel\Core\Classes\Interfaces\Http\BankPort
{
    interface Payment
    {

        /**
         * @param  integer $refId
         * @param  integer $status
         * @return void
         */
        public function onSuccessRespons(int $refId, int $status) : void;

        /**
         * @return void
         */
        public function onErrorRespons() : void;
    }
}