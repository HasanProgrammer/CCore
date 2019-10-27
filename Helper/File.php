<?php

if(!function_exists('readJsonFiles'))
{
    /**
     * @author  Hasan Karami
     * @version 1
     * @param   string $pathFile
     * @return  array
     */
    function readJsonFiles(string $pathFile) : array
    {
        try
        {
            if(file_exists($pathFile))
                return json_decode(file_get_contents($pathFile), true);
            throw new \Kernel\Exceptions\FileException('File: '.__FILE__.' --Line: '.__LINE__);
        }
        catch (\Kernel\Exceptions\FileException $fileException)
        {
            die($fileException->getMessage());
        }
    }
}

if(!function_exists('getArrayInFile'))
{
    /**
     * @author  Hasan Karami
     * @version 1
     * @param   string $path
     * @return  array
     */
    function getArrayInFile(string $path) : array
    {
        try
        {
            if(file_exists($path))
                return include $path;
            throw new \Kernel\Exceptions\FileException('File: '.__FILE__.' --Line: '.__LINE__);
        }
        catch (\Kernel\Exceptions\FileException $fileException)
        {
            die($fileException->getMessage());
        }
    }
}

if(!function_exists('config'))
{
    /**
     * @author  Hasan Karami
     * @version 1
     * @param   string  $config
     * @param   boolean $root
     * @return  array
     */
    function config(string $config, bool $root = false) : array
    {
        return ( $root == false ?  getArrayInFile('../Configs/'.$config.'.php') : getArrayInFile('Configs/'.$config.'.php'));
    }
}