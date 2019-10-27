<?php
/**
 * @author  HasanProgrammer
 * @version 1
 * @package CCore
 */
namespace Kernel\Core\Classes\Interfaces\Database
{
    interface ORM
    {
        public function transaction(\Kernel\Database\Pecod $ORM);
    }
}