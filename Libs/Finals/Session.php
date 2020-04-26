<?php

/**
 * @author  Hasan Karami
 * @version 1
 * @package CCore
 */
namespace Libs\Finals
{

    use Kernel\Exceptions\SessionException;

    final class Session
    {
        /**
         * @return void
         */
        public static function init()
        {
            if(session_status() == PHP_SESSION_NONE) session_start();
        }

        /**
         * @param  string | array   $key
         * @param  string | integer $value
         * @return boolean
         */
        public static function set($key, $value = null) : bool
        {
            try
            {
                if(is_array($key) && isAssoc($key))
                {
                    foreach($key as $Item => $value)
                        $_SESSION[$Item] = $value;
                    return true;
                }
                else
                {
                    if(!isset($value)) throw new SessionException('File: '.__FILE__.' --Line: '.__LINE__);
                    $_SESSION[$key] = $value;
                    return true;
                }
            }
            catch (SessionException $sessionException)
            {
                d( $sessionException->getMessage() );
            }

            return false;
        }

        /**
         * @param  string | array $key
         * @return boolean
         */
        public static function checkExE($key) : bool
        {
            if(is_array($key))
            {
                foreach($key as $Item)
                {
                    if(!isset($_SESSION[$Item])) return false;
                }
                return true;
            }
            else
            {
                if(isset($_SESSION[$key])) return true;
            }
            return false;
        }

        /**
         * @param  string $key
         * @return string | integer | array
         */
        public static function get(string $key)
        {
            try
            {
                if(is_array($key))
                {
                    $ExE = [];
                    foreach($key as $Item)
                    {
                        if(isset($_SESSION[$Item]))
                            $ExE[$Item] = $_SESSION[$Item];
                        else throw new SessionException('File: '.__FILE__.' --Line: '.__LINE__);
                    }
                    return $ExE;
                }
                else
                {
                    if(isset($_SESSION[$key]))
                        return $_SESSION[$key];
                    else throw new SessionException('File: '.__FILE__.' --Line: '.__LINE__);
                }
            }
            catch (SessionException $sessionException)
            {
                d( $sessionException->getMessage() );
            }

            return false;
        }

        /**
         * @param  string | array $key
         * @return boolean
         */
        public static function delete($key) : bool
        {
            try
            {
                if(is_array($key))
                {
                    foreach($key as $Item)
                    {
                        if(isset($_SESSION[$Item]))
                            $_SESSION[$Item] = null;
                        else throw new SessionException('File: '.__FILE__.' --Line: '.__LINE__);
                    }
                    return true;
                }
                else
                {
                    if(isset($_SESSION[$key])) $_SESSION[$key] = null;
                    else throw new SessionException('File: '.__FILE__.' --Line: '.__LINE__);
                    return true;
                }
            }
            catch (SessionException $sessionException)
            {
                d( $sessionException->getMessage() );
            }

            return false;
        }
    }
}