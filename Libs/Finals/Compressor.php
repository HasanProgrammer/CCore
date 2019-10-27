<?php
/**
 * @author  Hasan Karami
 * @version 1
 * @package CCore
 */
namespace Libs\Finals
{

    use MatthiasMullie\Minify;
    use MatthiasMullie\Minify\Exceptions\IOException;

    final class Compressor
    {
        /**
         * @return boolean
         */
        public static function run() : bool
        {
            try
            {
                if( isset(config('Provider', true)['Compressor']['JavaScript']) )
                {
                    foreach(config('Provider', true)['Compressor']['JavaScript'] as $item => $value)
                        (new Minify\JS($item))->minify("Storage/Asset/JavaScript/".$value.".js");
                }
                if( isset(config('Provider', true)['Compressor']['StyleSheet']) )
                {
                    foreach(config('Provider', true)['Compressor']['StyleSheet'] as $item => $value)
                        (new Minify\CSS($item))->minify("Storage/Asset/StyleSheet/".$value.".css");
                }
                return true;
            }
            catch (IOException $IOException)
            {
                die($IOException->getMessage());
            }
        }
    }
}