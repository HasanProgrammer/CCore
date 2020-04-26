<?php

/**
 * @author  Hasan Karami
 * @version 1
 * @package CCore
 */
namespace Kernel
{

    use PDO;
    use DateTime;
    use Exception;
    use Libs\Finals\ListFile;
    use Libs\Finals\Compressor;
    use Kernel\Database\Database;

    class Console
    {
        /**
         * @var string $host
         */
        private static string $host;

        /**
         * @var string $username
         */
        private static string $username;

        /**
         * @var string $password
         */
        private static string $password;

        /**
         * @return void
         */
        public function __construct()
        {
            self::$host       = (string)config("Database", true)["Mysql"]["Host"];
            self::$username   = (string)config("Database", true)["Mysql"]["Username"];
            self::$password   = (string)config("Database", true)["Mysql"]["Password"];
        }

        /**
         * @return void
         * @throws Exception
         */
        public function run()
        {
            while(true)
            {
                $this->getMainMenuOption();
                $console = trim( fgets(STDIN) ); // fgets(STDIN) = fgets( fopen("php://stdin" , "r"))
                switch($console)
                {
                    case 1:
                        $this->handleRunDatabase();
                    break;
                    case 2:
                        $this->handleInteractiveDatabase();
                    break;
                    case 3:
                        $this->handleRunServer();
                    break;
                    case 4:
                        $this->handleCreateMiddleware();
                    break;
                    case 5:
                        $this->handleCreateController();
                    break;
                    case 6:
                        $this->handleCreateModel();
                    break;
                    case 7:
                        $this->handleDatabaseStructure();
                    break;
                    case 8:
                        $this->handleCompressFile();
                    break;
                    case 9:
                        echo "There is no structure yet for this purpose!";
                        break;
                    case 10:
                        $this->handleCreateArea();
                        break;
                    case 11:
                        echo "cron job";
                        break;
                    case 12:
                        echo "Goodby!\n\n";
                        exit();
                    break;
                }
            }
        }

        /**
         * @return void
         */
        private function getMainMenuOption() : void
        {
            echo file_get_contents('Kernel/Core/Documents/Console.1.txt');
        }

        /**
         * @return void
         */
        private function getDatabaseStructureMenuOption() : void
        {
            echo file_get_contents('Kernel/Core/Documents/Console.2.txt');
        }

        /**
         * @return void
         */
        private function handleRunDatabase() : void
        {
            echo "\nThe mysql was successfully connected\n\n";
            shell_exec("mysqld --console");
        }

        /**
         * @return void
         */
        private function handleInteractiveDatabase() : void
        {
            echo "\nWelcome to shell mysql. Please insert your query...\n\n";
            while(true)
            {
                echo ">>> ";
                $console = (string)trim( fgets(STDIN) );
                if(strtolower($console) == "exit" || $console == "/")
                    break;
                echo "\nResult your query:\n";
                $query   = Database::getNewInstance()->getConnectionPDO()->prepare($console);
                if($query->execute())
                {
                    $result = $query->fetchAll(PDO::FETCH_ASSOC);
                    echo "\n";
                    print_r($result);
                    echo "\n";
                }
            }
        }

        /**
         * @return void
         */
        private function handleRunServer() : void
        {
            echo "Please insert your port: ";
            $console = trim( fgets(STDIN) );
            if(isset($console))
            {
                echo "\nThe server was successfully built\n\n";
                shell_exec("php -S 127.0.0.1:".$console);
            }
        }

        /**
         * @return void
         */
        private function handleCreateMiddleware() : void
        {
            while(true)
            {
                echo "\nPlease insert your middleware name: ";
                $console = trim( fgets(STDIN) );
                if((string)$console === "/")
                    break;
                if(!file_exists('Kernel/Http/Middleware/'.$console.'.php'))
                {
                    if( file_put_contents('Kernel/Http/Middleware/'.$console.'.php', str_replace('{middleware}', $console, file_get_contents('Kernel/Core/Templates/Console/Server/TemplateMiddleware.txt'))) )
                    {
                        echo "\nThe middleware ".$console." was successfully built in path << Kernel->Http->Middleware >>\n";
                        break;
                    }
                }
                echo "\nThe middleware ".$console." exists already in path << Kernel->Http->Middleware >>\n";
            }
        }

        /**
         * @return void
         */
        private function handleCreateController() : void
        {
            while(true)
            {
                echo "\nPlease insert your controller name: ";
                $console = trim( fgets(STDIN) );
                if((string)$console === "/")
                    break;
                $console = ucfirst(strtolower($console))."Controller";
                echo "\nDo you want to have a area for this controller? [y/n] ";
                $console_area = trim( fgets(STDIN) );
                if($console_area === "y")
                {
                    echo "\nPlease insert your area name: ";
                    $console_area_name = trim( fgets(STDIN) );
                    if( !is_dir('Areas/'.$console_area_name) )
                    {
                        echo "\nThe area ".$console_area_name." not exists in path << Areas >>\n";
                        continue;
                    }
                    if( !file_exists('Areas/'.$console_area_name.'/Controllers/'.$console.'.php') )
                    {
                        $template_controller = str_replace('{area}'                     , $console_area_name , file_get_contents('Kernel/Core/Templates/Console/Server/TemplateAreaController.txt'));
                        $template_controller = str_replace('{controller}Controller'     , $console           , $template_controller);
                        if( file_put_contents('Areas/'.$console_area_name.'/Controllers/'.$console.'.php'    , $template_controller) )
                        {
                            if(!is_dir('Areas/'.$console_area_name.'/Views/Controllers/'.$console)) mkdir('Areas/'.$console_area_name.'/Views/Controllers/'.$console);
                            if(!is_dir('WWW/JavaScript/Controllers/'                    .$console)) mkdir('WWW/JavaScript/Controllers/'                    .$console);
                            if(!is_dir('WWW/StyleSheet/Controllers/'                    .$console)) mkdir('WWW/StyleSheet/Controllers/'                    .$console);

                            file_put_contents('Areas/'.$console_area_name.'/Views/Controllers/'.$console.'/Index.php' , str_replace("{controller}Controller" , $console , file_get_contents('Kernel/Core/Templates/Console/Client/TemplateView.txt')));
                            file_put_contents('WWW/JavaScript/Controllers/'                    .$console.'/Index.js'  , file_get_contents('Kernel/Core/Templates/Console/Client/TemplateJavaScript.txt'));
                            file_put_contents('WWW/StyleSheet/Controllers/'                    .$console.'/Index.css' , file_get_contents('Kernel/Core/Templates/Console/Client/TemplateStyleSheet.txt'));
                            echo "\nThe controller ".$console." was successfully built in path << Areas->{$console_area_name}->Controllers >>\n";
                            break;
                        }
                    }
                    else echo "\nThe controller ".$console." exists already in path << Areas->{$console_area_name}->Controllers >>\n";
                }
                else if($console_area === "n")
                {
                    if(!file_exists('MVC/Controllers/'.$console.'.php'))
                    {
                        if(file_put_contents('MVC/Controllers/'.$console.'.php', str_replace('{controller}Controller', $console, file_get_contents('Kernel/Core/Templates/Console/Server/TemplateController.txt'))))
                        {
                            if(!is_dir('MVC/Views/Controllers/'      .$console)) mkdir('MVC/Views/Controllers/'      .$console);
                            if(!is_dir('WWW/JavaScript/Controllers/' .$console)) mkdir('WWW/JavaScript/Controllers/' .$console);
                            if(!is_dir('WWW/StyleSheet/Controllers/' .$console)) mkdir('WWW/StyleSheet/Controllers/' .$console);

                            file_put_contents('MVC/Views/Controllers/'      .$console.'/Index.php' , str_replace("{controller}Controller" , $console , file_get_contents('Kernel/Core/Templates/Console/Client/TemplateView.txt')));
                            file_put_contents('WWW/JavaScript/Controllers/' .$console.'/Index.js'  , file_get_contents('Kernel/Core/Templates/Console/Client/TemplateJavaScript.txt'));
                            file_put_contents('WWW/StyleSheet/Controllers/' .$console.'/Index.css' , file_get_contents('Kernel/Core/Templates/Console/Client/TemplateStyleSheet.txt'));
                            echo "\nThe controller ".$console." was successfully built in path << MVC->Controllers >>\n";
                            break;
                        }
                    }
                    else echo "\nThe controller ".$console." exists already in path << MVC->Controllers >>\n";
                }
                else break;
            }
        }
        /**
         * @return void
         */
        private function handleCreateModel() : void
        {
            while(true)
            {
                echo "\nPlease insert your model name: ";
                $console = trim(fgets(STDIN));
                if((string)$console === "/")
                    break;
                echo "\nDo you want to have a area for this model? [y/n] ";
                $console_area = trim(fgets(STDIN));
                if($console_area === "y")
                {
                    echo "\nPlease insert your area name: ";
                    $console_area_name = trim(fgets(STDIN));
                    if( !is_dir('Areas/'.$console_area_name) )
                    {
                        echo "\nThe area ".$console_area_name." not exists in path << Areas >>\n";
                        break;
                    }
                    if( !file_exists('Areas/'.$console_area_name.'/Models/Logic/'.$console.'.php') && !file_exists('Areas/'.$console_area_name.'/Models/DataModels/'.$console.'.php') )
                    {
                        $template_area_logic = str_replace('{logic}' , $console           , file_get_contents('Kernel/Core/Templates/Console/Server/TemplateLogic.txt'));
                        $template_area_model = str_replace('{model}' , $console           , file_get_contents('Kernel/Core/Templates/Console/Server/TemplateDataModels.txt'));
                        if(
                            file_put_contents('Areas/'.$console_area_name.'/Models/Logic/'     .$console.'.php' , $template_area_logic)
                            &&
                            file_put_contents('Areas/'.$console_area_name.'/Models/DataModels/'.$console.'.php' , $template_area_model)
                          )
                        {
                            echo "\nThe model ".$console." was successfully built in path << Areas->{$console_area_name}->Models->Logic->{$console} >> & << Areas->{$console_area_name}->Models->DataModels->{$console}\n";
                            break;
                        }
                    }
                    else echo "\nThe model ".$console." exists already in path << Areas->{$console_area_name}->Models->Logic->{$console} >> & << Areas->{$console_area_name}->Models->DataModels->{$console}\n";
                }
                else if($console_area === "n")
                {
                    if( !file_exists('MVC/Models/Logic/'.$console.'.php') && !file_exists('MVC/Models/DataModels/'.$console.'.php') )
                    {
                        if(
                            file_put_contents('MVC/Models/Logic/'      .$console.'.php', str_replace('{logic}' , $console , file_get_contents('Kernel/Core/Templates/Console/Server/TemplateLogic.php')))
                            &&
                            file_put_contents('MVC/Models/DataModels/' .$console.'.php', str_replace('{model}' , $console , file_get_contents('Kernel/Core/Templates/Console/Server/TemplateDataModels.php')))
                          )
                        {
                            echo "\nThe model ".$console." was successfully built in path << MVC->Models >>\n";
                            break;
                        }
                    }
                    else echo "\nThe model ".$console." exists already in path << MVC->Models >>\n";
                }
            }
        }

        /**
         * @return void
         * @throws Exception
         */
        public function handleDatabaseStructure() : void
        {
            while(true)
            {
                $this->getDatabaseStructureMenuOption();
                $console = trim( fgets(STDIN) );
                if((string)$console === "/")
                    break;
                switch($console)
                {
                    /* Difination The Migration's File */
                    case 1:
                    {
                        echo "\nPlease insert your table name: ";
                        $console = trim( fgets(STDIN) );
                        if((string)$console === "/")
                            break;
                        $date_time = new DateTime();
                        if(!file_exists('Database/Migrations/'.$console.'.sql'))
                        {
                            if(file_put_contents('Database/Migrations/Migration_'.$date_time->getTimestamp()."_".ucfirst(strtolower($console)).'.sql', str_replace("{table}", strtolower($console), file_get_contents('Kernel/Core/Templates/Console/Server/TemplateDatabase.txt'))))
                            {
                                echo "\nMessage: The table difination << ".$console." >> was successfully built in path << Database->Migrations >>\n";
                                break;
                            }
                        } else echo "\nMessage: The table difination << $console >> exists already in path << Database->Migrations >>\n";
                    }
                    break;
                    /* Run The All Migration's Files */
                    case 2:
                    {
                        Database::getNewInstance()->getConnectionPDO()->prepare(file_get_contents("Kernel/Core/Templates/Console/Server/Version.sql"))->execute();
                        $database = ListFile::exe('Database\\Migrations');
                        if(count($database) != 0)
                        {
                            $error = false;
                            foreach($database as $file)
                            {
                                if(file_exists($file))
                                {
                                    $query = Database::getNewInstance()->getConnectionPDO()->prepare("SELECT * FROM version WHERE tbl = '".explode('/', $file)[1]."'");
                                    if($query->execute())
                                    {
                                        if($query->rowCount() == 0)
                                        {
                                            $query = Database::getNewInstance()->getConnectionPDO()->prepare(file_get_contents($file));
                                            if($query->execute())
                                            {
                                                $query = Database::getNewInstance()->getConnectionPDO()->prepare("INSERT INTO version ( tbl , status ) VALUES ( '".explode('/', $file)[1]."' , 1 )");
                                                if($query->execute())
                                                    echo "\nMessage: The table << ".explode('/', $file)[1]." >> was successfully built\n";
                                            }
                                            else echo "\nMessage: The table << ".explode('/', $file)[1]." >> was not successfully built\n";
                                        }
                                        else
                                        {
                                            echo "\nMessage: The table << ".explode('/', $file)[1]." >> already exists!\n";
                                        }
                                    }
                                }
                                else
                                {
                                    echo "\nMessage: The table << ".explode('/', $file)[1]." >> not exists in path << Database->Migrations >>\n";
                                    $error = true;
                                }
                            }
                            if($error == true) echo "\nMessage: The tables was not successfully built completely\n";
                        } else echo "\nMessage: Not found any migration's files in path << Database->Migrations >>\n";
                    }
                    break;
                    /* Showing List Migration's Files */
                    case 3:
                    {
                        $database = ListFile::exe('Database\\Migrations');
                        if(count($database) != 0)
                        {
                            $X = 1;
                            foreach($database as $file)
                            {
                                if(file_exists($file))
                                    echo "\nTable-0$X: ".explode('/', $file)[1]."\n";
                                else
                                    echo "\nMessage: The table << ".explode('/', $file)[1]." >> not exists in path << Database->Migrations >>\n";
                                $X++;
                            }
                        }
                        else echo "\nMessage: The table structure not exists in path << Database->Migrations >>\n";
                    }
                    break;
                    /* Delete The Migration's Files */
                    case 4:
                    {
                        $database = ListFile::exe('Database\\Migrations');
                        if(count($database) != 0)
                        {
                            $error = false;
                            foreach($database as $file)
                            {
                                if(file_exists($file))
                                {
                                    $table = explode('_', explode('.', $file)[0]);
                                    $table = strtolower(end($table));
                                    if(Database::getNewInstance()->getConnectionPDO()->prepare("DROP TABLE IF EXISTS ".$table)->execute())
                                    {
                                        if(Database::getNewInstance()->getConnectionPDO()->prepare("DELETE FROM version WHERE tbl = '".explode('/', $file)[1]."'")->execute())
                                            echo "\nThe table << ".explode('/', $file)[1]." >> was successfully delete\n";
                                    }
                                    else echo "\nThe table << ".explode('/', $file)[1]." >> was not successfully delete\n";
                                }
                                else
                                {
                                    echo "\nThe table << ".explode('/', $file)[1]." >> not exists in path << Database->Migrations >>\n";
                                    $error = true;
                                }
                            }
                            if($error == true) echo "\nThe tables was not successfully delete completely!\n";
                        } else echo "\nThe table structure not exists in path << Database->Migrations >>\n";
                    }
                    break;
                    /* Showing The Tables Status In The Target's Database */
                    case 5:
                        $database = ListFile::exe('Database\\Migrations');
                        if(count($database) != 0)
                        {
                            foreach($database as $file)
                            {
                                if(file_exists($file))
                                {
                                    $query = Database::getNewInstance()->getConnectionPDO()->prepare("SELECT * FROM version WHERE tbl = '".explode("/", $file)[1]."'");
                                    if($query->execute())
                                    {
                                        if($query->rowCount() == 1 && (integer)$query->fetchAll(PDO::FETCH_ASSOC)[0]["status"] == 1)
                                        {
                                            echo "\nMessage: ".explode("/", $file)[1]." -> Active\n";
                                        }
                                        else
                                        {
                                            echo "\nMessage: ".explode("/", $file)[1]." -> Inactive\n";
                                        }
                                    }
                                }
                                else
                                {
                                    echo "\nMessage: The migration << ".explode("/", $file)[1]." >> not exists in path << Database->Migrations >>\n";
                                }
                            }
                        } else echo "\nMessage: Not found any migration's files in path << Database->Migrations >>\n";
                    break;
                }
            }
        }

        /**
         * @return void
         */
        private function handleCompressFile() : void
        {
            if(Compressor::run()) echo "\nThe files compressed successfully\n";
        }

        /**
         * @return void
         */
        private function handleCreateArea() : void
        {
            while(true)
            {
                echo "\nPlease insert your area name: ";
                $console = trim( fgets(STDIN) );
                if((string)$console === "/")
                    break;
                $console = ucfirst( strtolower($console) );
                $console = $console."Area";
                if(!is_dir('Areas/'.$console))
                {
                    mkdir('Areas/'.$console);

                    if(!is_dir('Areas/'.$console.'/Controllers'))             mkdir('Areas/'.$console.'/Controllers');
                    if(!is_dir('Areas/'.$console.'/Models'))                  mkdir('Areas/'.$console.'/Models');
                    if(!is_dir('Areas/'.$console.'/Models/DataModels'))       mkdir('Areas/'.$console.'/Models/DataModels');
                    if(!is_dir('Areas/'.$console.'/Models/Logic'))            mkdir('Areas/'.$console.'/Models/Logic');
                    if(!is_dir('Areas/'.$console.'/Models/StorageModels'))    mkdir('Areas/'.$console.'/Models/StorageModels');
                    if(!is_dir('Areas/'.$console.'/Models/Versions'))         mkdir('Areas/'.$console.'/Models/Versions');
                    if(!file_exists('Areas/'.$console.'/Models/Version.php')) file_put_contents('Areas/'.$console.'/Models/'.$console.'Version.php' , str_replace('{version}' , $console , file_get_contents('Kernel/Core/Templates/Console/Server/TemplateVersionModel.txt')));
                    if(!is_dir('Areas/'.$console.'/Views'))                   mkdir('Areas/'.$console.'/Views');
                    if(!is_dir('Areas/'.$console.'/Views/Controllers'))       mkdir('Areas/'.$console.'/Views/Controllers');
                    if(!is_dir('Areas/'.$console.'/Views/AJAX'))              mkdir('Areas/'.$console.'/Views/AJAX');
                    if(!is_dir('Areas/'.$console.'/Views/EMail'))             mkdir('Areas/'.$console.'/Views/EMail');
                    if(!is_dir('Areas/'.$console.'/Views/Layout'))            mkdir('Areas/'.$console.'/Views/Layout');
                    if(!is_dir('Areas/'.$console.'/Views/Partials'))          mkdir('Areas/'.$console.'/Views/Partials');
                    echo "\nThe area ".$console." was successfully built in path << Areas->{$console} >>\n";
                    break;
                }
                else echo "\nThe area ".$console." exists already in path << Areas->{$console} >>\n";
            }
        }
    }
}