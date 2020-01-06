<?php

/**
 * @author  Hasan Karami
 * @version 1
 * @package CCore
 */
namespace Kernel\Core\Interfaces\Http\BankPort\MVC
{

    use PDOStatement;

    interface Model
    {

        /**
         * @param  PDOStatement $PDOStatement
         * @return void
         */
        public function onReadyRecords(PDOStatement $PDOStatement) : void;

        /**
         * @param  string $links
         * @return void
         */
        public function onReadyLinks(string $links) : void;
    }
}