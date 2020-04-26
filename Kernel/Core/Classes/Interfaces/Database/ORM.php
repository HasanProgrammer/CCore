<?php

/**
 * @author  Hasan Karami
 * @version 1
 * @package CCore
 */
namespace Kernel\Core\Classes\Interfaces\Database
{

    use Kernel\Database\Pecod;

    interface ORM
    {
        /**
         * @param Pecod $ORM
         */
        public function transaction(Pecod $ORM);
    }
}