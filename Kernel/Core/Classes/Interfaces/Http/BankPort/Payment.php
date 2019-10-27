<?php
/**
 * @author  HasanProgrammer
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
        public function onSuccessRespons(int $refId, int $status);

        /**
         * @return void
         */
        public function onErrorRespons();
    }
}