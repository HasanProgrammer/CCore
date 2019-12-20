<?php
/**
 * @author  Hasan Karami
 * @version 1
 * @package CCore
 */
namespace Kernel\Http
{

    use stdClass;
    use SplFileInfo;

    final class Request
    {
        /**
         * @var boolean
         */
        private $isJsonFile = false;

        /**
         * @var array
         */
        private $jsonData;

        /**
         * @var array
         */
        private $getRequest    = [];

        /**
         * @var array
         */
        private $putRequest    = [];

        /**
         * @var array
         */
        private $postRequest   = [];

        /**
         * @var array
         */
        private $patchRequest  = [];

        /**
         * @var array
         */
        private $deleteRequest = [];

        /**
         * @return void
         */
        public function __construct()
        {
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
        private final function handleGetRequest()
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
        private final function handlePostRequest()
        {
            /* Handle Json Data */
            if( $this->isJsonFile )
            {
                $this->postRequest = $this->jsonData;
            }

            /* Handle Form Data */
            else if(isset($_POST))
            {
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
        private final function handlePutRequest()
        {
            /* Handle Json Data */
            if( $this->isJsonFile )
            {
                $this->putRequest = $this->jsonData;
            }

            /* Handle Form Data */
            else if(isset($_POST))
            {
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
        private final function handlePatchRequest()
        {
            /* Handle Json Data */
            if( $this->isJsonFile )
            {
                $this->patchRequest = $this->jsonData;
            }

            /* Handle Form Data */
            else if(isset($_POST))
            {
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
        private final function handleDeleteRequest()
        {
            /* Handle Json Data */
            if( $this->isJsonFile )
            {
                $this->deleteRequest = $this->jsonData;
            }

            /* Handle Form Data */
            else if(isset($_POST))
            {
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
        public final function isJsonFile() : bool
        {
            if($this->isJsonFile)
                return true;
            return false;
        }

        /**
         * @return boolean
         */
        public final function isGetMethod() : bool
        {
            if( strtoupper( $_SERVER["REQUEST_METHOD"] ) == "GET" )
                return true;
            return false;
        }

        /**
         * @return boolean
         */
        public final function isPostMethod() : bool
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
        public final function isPutMethod() : bool
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
                if(isset($_POST["POST"]) && $_POST["POST"] == "__PUT")
                    return true;
                return false;
            }
            return false;
        }

        /**
         * @return boolean
         */
        public final function isPatchMethod() : bool
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
                if(isset($_POST["POST"]) && $_POST["POST"] == "__PATCH")
                    return true;
                return false;
            }
            return false;
        }

        /**
         * @return boolean
         */
        public final function isDeleteMethod() : bool
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
                if(isset($_POST["POST"]) && $_POST["POST"] == "__DELETE")
                    return true;
                return false;
            }
            return false;
        }

        /**
         * @return mixed
         */
        public final function get()
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
                private $getRequest;

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
        public final function post()
        {
            /* Handle Json Data */
            if( $this->isJsonFile )
                return $this->postRequest;

            /* Handle Form Data */
            $anonymous = new class($this->postRequest) extends stdClass
            {
                /**
                 * @var array
                 */
                private $postRequest;

                /**
                 * @param array $postRequest
                 */
                public function __construct(array $postRequest)
                {
                    $this->postRequest = $postRequest;
                }

                /**
                 * @param  mixed
                 * @return boolean
                 */
                public function has($item) : bool
                {
                    return isInAssoc($this->postRequest, $item);
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

            if(isset($this->postRequest))
                foreach($this->postRequest as $item => $value)
                    $anonymous->{"get".ucfirst($item)} = $value;
            return $anonymous;
        }

        /**
         * @return mixed
         */
        public final function put()
        {
            /* Handle Json Data */
            if( $this->isJsonFile )
                return $this->putRequest;

            /* Handle Form Data */
            $anonymous = new class($this->putRequest) extends stdClass
            {
                /**
                 * @var array
                 */
                private $putRequest;

                /**
                 * @param array $putRequest
                 */
                public function __construct(array $putRequest)
                {
                    $this->putRequest = $putRequest;
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

            if(isset($this->putRequest))
                foreach($this->putRequest as $item => $value)
                    $anonymous->{"get".ucfirst($item)} = $value;
            return $anonymous;
        }

        /**
         * @return mixed
         */
        public final function patch()
        {
            /* Handle Json Data */
            if( $this->isJsonFile )
                return $this->patchRequest;

            /* Handle Form Data */
            $anonymous = new class($this->patchRequest) extends stdClass
            {
                /**
                 * @var array
                 */
                private $patchRequest;

                /**
                 * @param array $patchRequest
                 */
                public function __construct(array $patchRequest)
                {
                    $this->patchRequest = $patchRequest;
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

            if(isset($this->patchRequest))
                foreach($this->patchRequest as $item => $value)
                    $anonymous->{"get".ucfirst($item)} = $value;
            return $anonymous;
        }

        /**
         * @return mixed
         */
        public final function delete()
        {
            /* Handle Json Data */
            if( $this->isJsonFile )
                return $this->deleteRequest;

            /* Handle Form Data */
            $anonymous = new class($this->deleteRequest) extends stdClass
            {
                /**
                 * @var array
                 */
                private $deleteRequest;

                /**
                 * @param  array $deleteRequest
                 * @return void
                 */
                public function __construct(array $deleteRequest)
                {
                    $this->deleteRequest = $deleteRequest;
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

            if(isset($this->deleteRequest))
                foreach($this->deleteRequest as $item => $value)
                    $anonymous->{"get".ucfirst($item)} = $value;
            return $anonymous;
        }
    }
}