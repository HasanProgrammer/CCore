<?php

if(!function_exists("getQueryString"))
{
    /**
     * @param  string $target_file
     * @return string
     *
     * @throws Exception
     */
    function getQueryString(string $target_file) : ?string
    {
        try
        {
            if(file_exists("Database/QueryStrings/{$target_file}.php"))
            {
                return file_get_contents("Database/QueryStrings/{$target_file}.php");
            }
            throw new Exception("This file does not exist!");
        }
        catch(Exception $exception)
        {
            d( $exception->getMessage() );
        }

        return null;
    }
}