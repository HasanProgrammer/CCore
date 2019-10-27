<?php
/**
 * @author  Hasan Karami
 * @version 1
 * @package CCore
 */
namespace Libs\Finals
{
    final class ListFile
    {
        private static $files = [];
        /**
         * @param  string $file
         * @return array
         */
        public static final function exe(string $file) : array
        {
            self::$files = [];
            foreach(glob($file.'/*') as $item)
            {
                if(is_dir($item))
                    ListFile::exe($item);
                self::$files[] = $item;
            }
            return self::$files;
        }
    }
}