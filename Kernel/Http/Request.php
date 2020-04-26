<?php

/**
 * @author  Hasan Karami
 * @version 1
 * @package CCore
 */
namespace Kernel\Http
{

    use stdClass;
    use Exception;
    use SplFileInfo;

    class Request
    {
        /**
         * @var boolean $isFile
         */
        private bool $isFile = false;

        /**
         * @var boolean $isJsonFile
         */
        private bool $isJsonFile = false;

        /**
         * @var array $fileData
         */
        private array $fileData;

        /**
         * @var array $jsonData
         */
        private array $jsonData;

        /**
         * @var array $getRequest
         */
        private array $getRequest = [];

        /**
         * @var array $putRequest
         */
        private array $putRequest = [];

        /**
         * @var array $putFileRequest
         */
        private array $putFileRequest = [];

        /**
         * @var array $postRequest
         */
        private array $postRequest = [];

        /**
         * @var array $postFileRequest
         */
        private array $postFileRequest = [];

        /**
         * @var array $patchRequest
         */
        private array $patchRequest = [];

        /**
         * @var array $patchFileRequest
         */
        private array $patchFileRequest = [];

        /**
         * @var array $deleteRequest
         */
        private array $deleteRequest = [];

        /**
         * @var array $deleteFileRequest
         */
        private array $deleteFileRequest = [];

        /**
         * @return void
         */
        public function __construct()
        {
            /* Handle File Data */
            if(isset($_FILES))
            {
                $this->isFile = true;
            }

            /* Handle Json Data */
            $receive = file_get_contents("php://input");
            if( $receive != null )
            {
                header("Content-Type: application/json");
                $jsonData = json_decode( $receive , true );
                if( $jsonData != null )
                {
                    $this->isJsonFile = true;
                    $this->jsonData   = $jsonData;
                }
                else $this->isJsonFile = false;
            }

            /* Handle Form Data */
            if     ( $this->isPostMethod()   ) $this->handlePostRequest();
            else if( $this->isPutMethod()    ) $this->handlePutRequest();
            else if( $this->isPatchMethod()  ) $this->handlePatchRequest();
            else if( $this->isDeleteMethod() ) $this->handleDeleteRequest();
            else                               $this->handleGetRequest();
        }

        /**
         * @return void
         */
        private function handleGetRequest() : void
        {
            /* Handle Json Data */
            if( $this->isJsonFile )
            {
                $this->getRequest = $this->jsonData;
            }

            /* Handle Form Data */
            else if(isset($_GET))
            {
                foreach($_GET as $item => $value)
                    $this->getRequest[$item] = immunization($value);
            }
        }

        /**
         * @return void
         */
        private function handlePostRequest() : void
        {
            /* Handle Json Data */
            if( $this->isJsonFile )
            {
                $this->postRequest = $this->jsonData;
            }

            /* Handle Form Data */
            else if(isset($_POST))
            {
                /* Handle File Data */
                if(isset($_FILES))
                {
                    foreach($_FILES as $item => $value)
                    {
                        $this->postFileRequest[$item] = $value;
                    }
                }

                foreach($_POST as $item => $value)
                {
                    if($item == 'POST' && $value == '__POST')
                        continue;
                    $this->postRequest[$item] = immunization($value);
                }
            }
        }

        /**
         * @return void
         */
        private function handlePutRequest() : void
        {
            /* Handle Json Data */
            if( $this->isJsonFile )
            {
                $this->putRequest = $this->jsonData;
            }

            /* Handle Form Data */
            else if(isset($_POST))
            {
                /* Handle File Data */
                if(isset($_FILES))
                {
                    foreach($_FILES as $item => $value)
                    {
                        $this->postFileRequest[$item] = $value;
                    }
                }

                foreach($_POST as $item => $value)
                {
                    if($item == 'PUT' && $value == '__PUT')
                        continue;
                    $this->putRequest[$item] = immunization($value);
                }
            }
        }

        /**
         * @return void
         */
        private function handlePatchRequest() : void
        {
            /* Handle Json Data */
            if( $this->isJsonFile )
            {
                $this->patchRequest = $this->jsonData;
            }

            /* Handle Form Data */
            else if(isset($_POST))
            {
                /* Handle File Data */
                if(isset($_FILES))
                {
                    foreach($_FILES as $item => $value)
                    {
                        $this->postFileRequest[$item] = $value;
                    }
                }

                foreach($_POST as $item => $value)
                {
                    if($item == 'PATCH' && $value == '__PATCH')
                        continue;
                    $this->patchRequest[$item] = immunization($value);
                }
            }
        }

        /**
         * @return void
         */
        private function handleDeleteRequest() : void
        {
            /* Handle Json Data */
            if( $this->isJsonFile )
            {
                $this->deleteRequest = $this->jsonData;
            }

            /* Handle Form Data */
            else if(isset($_POST))
            {
                /* Handle File Data */
                if(isset($_FILES))
                {
                    foreach($_FILES as $item => $value)
                    {
                        $this->postFileRequest[$item] = $value;
                    }
                }

                foreach($_POST as $item => $value)
                {
                    if($item == 'DELETE' && $value == '__DELETE')
                        continue;
                    $this->deleteRequest[$item] = immunization($value);
                }
            }
        }

        /**
         * @return boolean
         */
        public function isFile() : bool
        {
            if($this->isFile)
                return true;
            return false;
        }

        /**
         * @return boolean
         */
        public function isJsonFile() : bool
        {
            if($this->isJsonFile)
                return true;
            return false;
        }

        /**
         * @return boolean
         */
        public function isGetMethod() : bool
        {
            if( strtoupper( $_SERVER["REQUEST_METHOD"] ) == "GET" )
                return true;
            return false;
        }

        /**
         * @return boolean
         */
        public function isPostMethod() : bool
        {
            /* Handle Json Data */
            if( $this->isJsonFile )
            {
                if( strtoupper( $_SERVER["REQUEST_METHOD"] ) == "POST" )
                    return true;
                return false;
            }

            /* Handle Form Data */
            if( strtoupper( $_SERVER['REQUEST_METHOD'] ) == "POST" )
            {
                if(isset($_POST["POST"]) && $_POST["POST"] == "__POST")
                    return true;
                return false;
            }
            return false;
        }

        /**
         * @return boolean
         */
        public function isPutMethod() : bool
        {
            /* Handle Json Data */
            if( $this->isJsonFile )
            {
                if( strtoupper( $_SERVER["REQUEST_METHOD"] ) == "PUT" )
                    return true;
                return false;
            }

            /* Handle Form Data */
            if( strtoupper( $_SERVER['REQUEST_METHOD'] ) == "POST" )
            {
                if(isset($_POST["PUT"]) && $_POST["PUT"] == "__PUT")
                    return true;
                return false;
            }
            return false;
        }

        /**
         * @return boolean
         */
        public function isPatchMethod() : bool
        {
            /* Handle Json Data */
            if( $this->isJsonFile )
            {
                if( strtoupper( $_SERVER["REQUEST_METHOD"] ) == "PATCH" )
                    return true;
                return false;
            }

            /* Handle Form Data */
            if( strtoupper( $_SERVER['REQUEST_METHOD'] ) == "POST" )
            {
                if(isset($_POST["PATCH"]) && $_POST["PATCH"] == "__PATCH")
                    return true;
                return false;
            }
            return false;
        }

        /**
         * @return boolean
         */
        public function isDeleteMethod() : bool
        {
            /* Handle Json Data */
            if( $this->isJsonFile )
            {
                if( strtoupper( $_SERVER["REQUEST_METHOD"] ) == "DELETE" )
                    return true;
                return false;
            }

            /* Handle Form Data */
            if( strtoupper( $_SERVER['REQUEST_METHOD'] ) == "POST" )
            {
                if(isset($_POST["DELETE"]) && $_POST["DELETE"] == "__DELETE")
                    return true;
                return false;
            }
            return false;
        }

        /**
         * @return mixed
         */
        public function get()
        {
            /* Handle Json Data */
            if( $this->isJsonFile )
                return $this->getRequest;

            /* Handle Form Data */
            $anonymous = new class($this->getRequest) extends stdClass
            {
                /**
                 * @var array
                 */
                private array $getRequest;

                /**
                 * @param  array $getRequest
                 * @return void
                 */
                public function __construct(array $getRequest)
                {
                    $this->getRequest = $getRequest;
                }

                /**
                 * @param  mixed
                 * @return boolean
                 */
                public function has($item) : bool
                {
                    return isInAssoc($this->getRequest , $item);
                }

                /**
                 * @return array
                 */
                public function toArray() : array
                {
                    return $this->getRequest;
                }

                /**
                 * @return string
                 */
                public function toJson() : string
                {
                    return json_encode($this->getRequest);
                }
            };

            if(isset($this->getRequest))
                foreach($this->getRequest as $item => $value)
                    $anonymous->{"get".ucfirst($item)} = $value;
            return $anonymous;
        }

        /**
         * @return mixed
         */
        public function post()
        {
            /* Handle Json Data */
            if( $this->isJsonFile )
                return $this->postRequest;

            /* Handle Form Data */
            $anonymous = new class($this->postRequest , $this->postFileRequest) extends stdClass
            {
                /**
                 * @var array
                 */
                private array $postRequest;

                /**
                 * @var array
                 */
                private array $postFileRequest;

                /**
                 * @var Request $request
                 */
                private Request $request;

                /**
                 * @param array  $postRequest
                 * @param array  $postFileRequest
                 */
                public function __construct(array $postRequest , array $postFileRequest)
                {
                    $this->postRequest     = $postRequest;
                    $this->postFileRequest = $postFileRequest;
                }

                /**
                 * @return stdClass
                 */
                public function files() : stdClass
                {
                    $anonymous = new class($this->postFileRequest, $this) extends stdClass
                    {
                        /**
                         * @var stdClass $this
                         */
                        private stdClass $this;

                        /**
                         * @var array
                         */
                        private array $postFileRequest;

                        /**
                         * @param array    $postFiles
                         * @param stdClass $stdClass
                         */
                        public function __construct(array $postFiles, stdClass $stdClass)
                        {
                            $this->this = $stdClass;
                            $this->postFileRequest = $postFiles;
                        }

                        /**
                         * @param  callable $working
                         * @return void
                         */
                        public function each(callable $working) : void
                        {
                            foreach($this->postFileRequest as $item => $value)
                                call_user_func($working, $this->this, $item);
                        }
                    };

                    $anonymous->count = count( $this->postFileRequest );
                    return $anonymous;
                }

                /**
                 * @param  mixed $item
                 * @return boolean
                 */
                public function hasFile($item) : bool
                {
                    return isInAssoc($this->postFileRequest, $item);
                }

                /**
                 * @param  mixed $item
                 * @return boolean
                 */
                public function has($item) : bool
                {
                    return isInAssoc($this->postRequest, $item);
                }

                /**
                 * @param  string   $file
                 * @param  string   $path
                 * @param  callable $config
                 * @return stdClass
                 *
                 * @throws Exception
                 */
                public function uploadToServer(string $file, string $path = null, callable $config) : stdClass
                {
                    $config = call_user_func($config);
                    if(!isset($config["type"]) || !isset($config["format"]) || !isset($config["size"]))
                        throw new Exception("0");

                    //Handle Setting
                    $type   = explode("|", $config["type"]);
                    $format = explode("|", $config["format"]);
                    $size   = $config["size"];

                    //Handle Target's Name File
                    $file_name_array = explode(".", $this->postFileRequest[$file]["name"]);
                    $file_extention  = strtolower(end($file_name_array));
                    $new_file_name   = md5(time().$this->postFileRequest[$file]["name"]).".".$file_extention;
                    $target_path     = ( isset($path) ? $path."/" : "Storage/Upload/" ).$new_file_name;

                    //Main Process
                    if($this->hasFile($file))
                    {
                        if($this->postFileRequest[$file]["error"] == 0)
                        {
                            if(isInAssoc($type, $this->postFileRequest[$file]["type"]))
                            {
                                if($this->postFileRequest[$file]["size"] >= $size["min"] && $this->postFileRequest[$file]["size"] <= $size["max"])
                                {
                                    move_uploaded_file($this->postFileRequest[$file]["tmp_name"], $target_path);
                                    $info = new stdClass();
                                    $info->targetDirectory = $target_path;
                                    $info->targetNameFile  = $new_file_name;
                                    return $info;
                                }
                                throw new Exception("1");
                            }
                            throw new Exception("The file");
                        }
                        throw new Exception("The file has an error!");
                    }
                    throw new Exception("The file dose not exists!");
                }

                /**
                 * @return array
                 */
                public function toArray() : array
                {
                    return $this->postRequest;
                }

                /**
                 * @return string
                 */
                public function toJson() : string
                {
                    return json_encode($this->postRequest);
                }
            };

            if(isset($this->postFileRequest))
                foreach($this->postFileRequest as $item => $value)
                    $anonymous->{"get".ucfirst($item)} = $value;

            if(isset($this->postRequest))
                foreach($this->postRequest as $item => $value)
                    $anonymous->{"get".ucfirst($item)} = $value;
            return $anonymous;
        }

        /**
         * @return mixed
         */
        public function put()
        {
            /* Handle Json Data */
            if( $this->isJsonFile )
                return $this->putRequest;

            /* Handle Form Data */
            $anonymous = new class($this->putRequest , $this->putFileRequest , $this) extends stdClass
            {
                /**
                 * @var array
                 */
                private array $putRequest;

                /**
                 * @var array $putFileRequest
                 */
                private array $putFileRequest;

                /**
                 * @var Request $request
                 */
                private Request $request;

                /**
                 * @param array   $putRequest
                 * @param array   $putFileRequest
                 * @param Request $request
                 */
                public function __construct(array $putRequest , array $putFileRequest, Request $request)
                {
                    $this->putRequest     = $putRequest;
                    $this->putFileRequest = $putFileRequest;
                    $this->request        = $request;
                }

                /**
                 * @return stdClass
                 */
                public function files() : stdClass
                {
                    $anonymous = new class($this->putFileRequest, $this) extends stdClass
                    {
                        /**
                         * @var stdClass $this
                         */
                        private stdClass $this;

                        /**
                         * @var array
                         */
                        private array $putFileRequest;

                        /**
                         * @param array    $putFiles
                         * @param stdClass $stdClass
                         */
                        public function __construct(array $putFiles, stdClass $stdClass)
                        {
                            $this->this = $stdClass;
                            $this->putFileRequest = $putFiles;
                        }

                        /**
                         * @param  callable $working
                         * @return void
                         */
                        public function each(callable $working) : void
                        {
                            foreach($this->putFileRequest as $item => $value)
                                call_user_func($working, $this->this, $item);
                        }
                    };

                    $anonymous->count = count( $this->putFileRequest );
                    return $anonymous;
                }

                /**
                 * @param  mixed
                 * @return boolean
                 */
                public function has($item) : bool
                {
                    return isInAssoc($this->putRequest, $item);
                }

                /**
                 * @param  mixed $item
                 * @return boolean
                 */
                public function hasFile($item) : bool
                {
                    return isInAssoc($this->putFileRequest, $item);
                }

                /**
                 * @param  string   $file
                 * @param  string   $path
                 * @param  callable $config
                 * @return stdClass
                 *
                 * @throws Exception
                 */
                public function uploadToServer(string $file, string $path = null, callable $config) : stdClass
                {
                    $config = call_user_func($config);
                    if(!isset($config["type"]) || !isset($config["format"]) || !isset($config["size"]))
                        throw new Exception("0");

                    //Handle Setting
                    $type   = explode("|", $config["type"]);
                    $format = explode("|", $config["format"]);
                    $size   = $config["size"];

                    //Handle Target's Name File
                    $file_name_array = explode(".", $this->putFileRequest[$file]["name"]);
                    $file_extention  = strtolower(end($file_name_array));
                    $new_file_name   = md5(time().$this->putFileRequest[$file]["name"]).".".$file_extention;
                    $target_path     = ( isset($path) ? $path."/" : "Storage/Upload/" ).$new_file_name;

                    //Main Process
                    if($this->hasFile($file))
                    {
                        if($this->putFileRequest[$file]["error"] == 0)
                        {
                            if(isInAssoc($type, $this->putFileRequest[$file]["type"]))
                            {
                                if($this->putFileRequest[$file]["size"] >= $size["min"] && $this->putFileRequest[$file]["size"] <= $size["max"])
                                {
                                    move_uploaded_file($this->putFileRequest[$file]["tmp_name"], $target_path);
                                    $info = new stdClass();
                                    $info->targetDirectory = $target_path;
                                    $info->targetNameFile  = $new_file_name;
                                    return $info;
                                }
                                throw new Exception("1");
                            }
                            throw new Exception("The file");
                        }
                        throw new Exception("The file has an error!");
                    }
                    throw new Exception("The file dose not exists!");
                }

                /**
                 * @return array
                 */
                public function toArray() : array
                {
                    return $this->putRequest;
                }

                /**
                 * @return string
                 */
                public function toJson() : string
                {
                    return json_encode($this->putRequest);
                }
            };

            if(isset($this->putFileRequest))
                foreach($this->putFileRequest as $item => $value)
                    $anonymous->{"get".ucfirst($item)} = $value;

            if(isset($this->putRequest))
                foreach($this->putRequest as $item => $value)
                    $anonymous->{"get".ucfirst($item)} = $value;
            return $anonymous;
        }

        /**
         * @return mixed
         */
        public function patch()
        {
            /* Handle Json Data */
            if( $this->isJsonFile )
                return $this->patchRequest;

            /* Handle Form Data */
            $anonymous = new class($this->patchRequest , $this->patchFileRequest) extends stdClass
            {
                /**
                 * @var array
                 */
                private array $patchRequest;

                /**
                 * @var array $patchFileRequest
                 */
                private array $patchFileRequest;

                /**
                 * @param array   $patchRequest
                 * @param array   $patchFileRequest
                 */
                public function __construct(array $patchRequest, array $patchFileRequest)
                {
                    $this->patchRequest     = $patchRequest;
                    $this->patchFileRequest = $patchFileRequest;
                }

                /**
                 * @return stdClass
                 */
                public function files() : stdClass
                {
                    $anonymous = new class($this->patchFileRequest, $this) extends stdClass
                    {
                        /**
                         * @var stdClass $this
                         */
                        private stdClass $this;

                        /**
                         * @var array
                         */
                        private array $postFileRequest;

                        /**
                         * @param array    $postFiles
                         * @param stdClass $stdClass
                         */
                        public function __construct(array $postFiles, stdClass $stdClass)
                        {
                            $this->this = $stdClass;
                            $this->postFileRequest = $postFiles;
                        }

                        /**
                         * @param  callable $working
                         * @return void
                         */
                        public function each(callable $working) : void
                        {
                            foreach($this->postFileRequest as $item => $value)
                                call_user_func($working, $this->this, $item);
                        }
                    };

                    $anonymous->count = count( $this->postFileRequest );
                    return $anonymous;
                }

                /**
                 * @param  mixed
                 * @return boolean
                 */
                public function has($item) : bool
                {
                    return isInAssoc($this->patchRequest, $item);
                }

                /**
                 * @param  mixed $item
                 * @return boolean
                 */
                public function hasFile($item) : bool
                {
                    return isInAssoc($this->patchRequest, $item);
                }

                /**
                 * @param  string   $file
                 * @param  string   $path
                 * @param  callable $config
                 * @return stdClass
                 *
                 * @throws Exception
                 */
                public function uploadToServer(string $file, string $path = null, callable $config) : stdClass
                {
                    $config = call_user_func($config);
                    if(!isset($config["type"]) || !isset($config["format"]) || !isset($config["size"]))
                        throw new Exception("0");

                    //Handle Setting
                    $type   = explode("|", $config["type"]);
                    $format = explode("|", $config["format"]);
                    $size   = $config["size"];

                    //Handle Target's Name File
                    $file_name_array = explode(".", $this->patchFileRequest[$file]["name"]);
                    $file_extention  = strtolower(end($file_name_array));
                    $new_file_name   = md5(time().$this->patchFileRequest[$file]["name"]).".".$file_extention;
                    $target_path     = ( isset($path) ? $path."/" : "Storage/Upload/" ).$new_file_name;

                    //Main Process
                    if($this->hasFile($file))
                    {
                        if($this->postFileRequest[$file]["error"] == 0)
                        {
                            if(isInAssoc($type, $this->patchFileRequest[$file]["type"]))
                            {
                                if($this->patchFileRequest[$file]["size"] >= $size["min"] && $this->patchFileRequest[$file]["size"] <= $size["max"])
                                {
                                    move_uploaded_file($this->patchFileRequest[$file]["tmp_name"], $target_path);
                                    $info = new stdClass();
                                    $info->targetDirectory = $target_path;
                                    $info->targetNameFile  = $new_file_name;
                                    return $info;
                                }
                                throw new Exception("1");
                            }
                            throw new Exception("2");
                        }
                        throw new Exception("3");
                    }
                    throw new Exception("4");
                }

                /**
                 * @return array
                 */
                public function toArray() : array
                {
                    return $this->patchRequest;
                }

                /**
                 * @return string
                 */
                public function toJson() : string
                {
                    return json_encode($this->patchRequest);
                }
            };

            if(isset($this->patchFileRequest))
                foreach($this->patchFileRequest as $item => $value)
                    $anonymous->{"get".ucfirst($item)} = $value;

            if(isset($this->patchRequest))
                foreach($this->patchRequest as $item => $value)
                    $anonymous->{"get".ucfirst($item)} = $value;
            return $anonymous;
        }

        /**
         * @return mixed
         */
        public function delete()
        {
            /* Handle Json Data */
            if( $this->isJsonFile )
                return $this->deleteRequest;

            /* Handle Form Data */
            $anonymous = new class($this->deleteRequest , $this->deleteFileRequest) extends stdClass
            {
                /**
                 * @var array
                 */
                private array $deleteRequest;

                /**
                 * @var array $deleteFileRequest
                 */
                private array $deleteFileRequest;

                /**
                 * @param  array $deleteRequest
                 * @param  array $deleteFileRequest
                 * @return void
                 */
                public function __construct(array $deleteRequest , array $deleteFileRequest)
                {
                    $this->deleteRequest     = $deleteRequest;
                    $this->deleteFileRequest = $deleteFileRequest;
                }

                /**
                 * @return stdClass
                 */
                public function files() : stdClass
                {
                    $anonymous = new class($this->deleteFileRequest, $this) extends stdClass
                    {
                        /**
                         * @var stdClass $this
                         */
                        private stdClass $this;

                        /**
                         * @var array
                         */
                        private array $postFileRequest;

                        /**
                         * @param array    $postFiles
                         * @param stdClass $stdClass
                         */
                        public function __construct(array $postFiles, stdClass $stdClass)
                        {
                            $this->this = $stdClass;
                            $this->postFileRequest = $postFiles;
                        }

                        /**
                         * @param  callable $working
                         * @return void
                         */
                        public function each(callable $working) : void
                        {
                            foreach($this->postFileRequest as $item => $value)
                                call_user_func($working, $this->this, $item);
                        }
                    };

                    $anonymous->count = count( $this->postFileRequest );
                    return $anonymous;
                }

                /**
                 * @param  mixed
                 * @return boolean
                 */
                public function has($item) : bool
                {
                    return isInAssoc($this->deleteRequest, $item);
                }

                /**
                 * @param  mixed
                 * @return boolean
                 */
                public function hasFile($item) : bool
                {
                    return isInAssoc($this->deleteFileRequest, $item);
                }

                /**
                 * @param  string   $file
                 * @param  string   $path
                 * @param  callable $config
                 * @return stdClass
                 *
                 * @throws Exception
                 */
                public function uploadToServer(string $file, string $path = null, callable $config) : stdClass
                {
                    $config = call_user_func($config);
                    if(!isset($config["type"]) || !isset($config["format"]) || !isset($config["size"]))
                        throw new Exception("0");

                    //Handle Setting
                    $type   = explode("|", $config["type"]);
                    $format = explode("|", $config["format"]);
                    $size   = $config["size"];

                    //Handle Target's Name File
                    $file_name_array = explode(".", $this->deleteFileRequest[$file]["name"]);
                    $file_extention  = strtolower(end($file_name_array));
                    $new_file_name   = md5(time().$this->deleteFileRequest[$file]["name"]).".".$file_extention;
                    $target_path     = ( isset($path) ? $path."/" : "Storage/Upload/" ).$new_file_name;

                    //Main Process
                    if($this->hasFile($file))
                    {
                        if($this->deleteFileRequest[$file]["error"] == 0)
                        {
                            if(isInAssoc($type, $this->deleteFileRequest[$file]["type"]))
                            {
                                if($this->deleteFileRequest[$file]["size"] >= $size["min"] && $this->deleteFileRequest[$file]["size"] <= $size["max"])
                                {
                                    move_uploaded_file($this->deleteFileRequest[$file]["tmp_name"], $target_path);
                                    $info = new stdClass();
                                    $info->targetDirectory = $target_path;
                                    $info->targetNameFile  = $new_file_name;
                                    return $info;
                                }
                                throw new Exception("1");
                            }
                            throw new Exception("The file");
                        }
                        throw new Exception("The file has an error!");
                    }
                    throw new Exception("The file dose not exists!");
                }

                /**
                 * @return array
                 */
                public function toArray() : array
                {
                    return $this->deleteRequest;
                }

                /**
                 * @return string
                 */
                public function toJson() : string
                {
                    return json_encode($this->deleteRequest);
                }
            };

            if(isset($this->deleteFileRequest))
                foreach($this->deleteFileRequest as $item => $value)
                    $anonymous->{"get".ucfirst($item)} = $value;

            if(isset($this->deleteRequest))
                foreach($this->deleteRequest as $item => $value)
                    $anonymous->{"get".ucfirst($item)} = $value;
            return $anonymous;
        }
    }
}