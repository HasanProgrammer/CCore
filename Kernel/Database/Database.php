<?php

/**
 * @author  Hasan Karami
 * @version 1
 * @package CCore
 */
namespace Kernel\Database
{

    use PDO;
    use PDOException;

    final class Database
    {
        /**
         * @var Database $instance
         */
        private static Database $instance;

        /**
         * @var PDO $connectionPDO
         */
        private static PDO $connectionPDO;

        /**
         * @return void
         */
        private function __construct()
        {
            try
            {
                self::$connectionPDO = new PDO
                (
                    config('Database')['Mysql']['Driver'].":host="  .
                    config('Database')['Mysql']['Host']  .";dbname=".
                    config('Database')['Mysql']['Database'] ,
                    config('Database')['Mysql']['Username'] ,
                    config('Database')['Mysql']['Password']
                );
            }
            catch (PDOException $e)
            {
                d( $e->getMessage() );
            }
        }

        /**
         * @return self
         */
        public static function getNewInstance() : self
        {
            if(!isset(self::$instance)) self::$instance = new Database();
            return self::$instance;
        }

        /**
         * @return PDO
         */
        public function getConnectionPDO() : PDO
        {
            return self::$connectionPDO;
        }
    }
}