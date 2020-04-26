<?php

/**
 * @author  Hasan Karami
 * @version 1
 * @package CCore
 */
namespace Libs\Wrappers
{

    use ZipArchive;

    final class Zipper
    {
        /**
         * @var ZipArchive $zip
         */
        private ZipArchive $zip;

        /**
         * @var array $files
         */
        private array $files = [];

        /**
         * @return void
         */
        public final function __construct() 
        {
            $this->zip = new ZipArchive();
        }

        /**
         * @param  string | integer | array $input
         * @return Zipper
         */
        public final function add($input) : Zipper
        {
            if(is_array($input))
                $this->files = array_merge($this->files, $input);
            else
                $this->files[] = $input;
            return $this;
        }

        /**
         * @param  string | integer | array $location
         * @return void
         */
        public final function store($location) : void
        {
            if(count($this->files) && isset($location))
            {
                foreach($this->files as $Index => $File)
                {
                    if(!file_exists($File))
                        unset($this->files[$Index]);
                }
                if($this->zip->open($location, file_exists($location) ? \ZipArchive::OVERWRITE : \ZipArchive::CREATE)) 
                {
                    foreach($this->files as $File)
                        $this->zip->addFile($File, $File);
                    $this->zip->close();
                }
            }
        }
    }
}