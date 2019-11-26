<?php
/**
 * @author  Hasan Karami
 * @version 1
 * @package CCore
 */
namespace Kernel\Http\Route
{
    
    use Kernel\Http\Routes\GetPath;
    use Kernel\Http\Routes\PutPath;
    use Kernel\Http\Routes\PostPath;
    use Kernel\Http\Routes\PatchPath;
    use Kernel\Http\Routes\DeletePath;
    
    final class RouterRunner
    {
        /**
         * @param  string $dirname
         * @return void
         */
        public static function run(string $dirname)
        {
            HttpRequest::start($dirname);
            GetPath::run();
            PutPath::run();
            PostPath::run();
            PatchPath::run();
            DeletePath::run();
            include_once 'Routes/DERoute.php';
            include_once 'Routes/GERoute.php';
            include_once 'Routes/PARoute.php';
            include_once 'Routes/PORoute.php';
            include_once 'Routes/PURoute.php';
            HttpRequest::exe();
        }
    }
}