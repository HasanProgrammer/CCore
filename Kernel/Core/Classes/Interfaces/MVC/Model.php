<?php
/**
 * @author  HasanProgrammer
 * @version 1
 * @package CCore
 */
namespace Kernel\Core\Interfaces\Http\BankPort\MVC
{
    interface Model
    {

        /**
         * @param  \PDOStatement $PDOStatement
         * @return void
         */
        public function onReadyRecords(\PDOStatement $PDOStatement);

        /**
         * @param  string $links
         * @return void
         */
        public function onReadyLinks(string $links);
    }
}