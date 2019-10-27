<?php
/**
 * @author  HasanProgrammer
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
        public function onSuccessRespons(string $authority, int $status);

        /**
         * @param  string  $authority
         * @param  integer $status
         * @return void
         */
        public function onErrorRespons(string $authority, int $status);
    }
}