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

    class Database
    {
        private static $instance;
        private static $connectionPDO;
        /**
         * @return void
         */
        private function __construct()
        {
            try
            {
                self::$connectionPDO = new PDO(
                    config('Database')['Mysql']['Driver'].":host="  .
                    config('Database')['Mysql']['Host']  .";dbname=".
                    config('Database')['Mysql']['Database'] ,
                    config('Database')['Mysql']['Username'] ,
                    config('Database')['Mysql']['Password']
                );
            }
            catch (PDOException $e)
            {
                dd( $e->getMessage() );
            }
        }
        /**
         * @return Database
         */
        public static function getNewInstance() : Database
        {
            if(!isset(self::$instance)) self::$instance = new Database();
            return self::$instance;
        }
        /**
         * @return PDO
         */
        public final function getConnectionPDO() : PDO
        {
            return self::$connectionPDO;
        }
    }
}