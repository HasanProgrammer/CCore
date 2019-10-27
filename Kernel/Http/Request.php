<?php
/**
 * @author  Hasan Karami
 * @version 1
 * @package CCore
 */
namespace Kernel\Http
{
    use stdClass;
    final class Request
    {

        private $getRequest    = [];
        private $putRequest    = [];
        private $postRequest   = [];
        private $patchRequest  = [];
        private $deleteRequest = [];

        /**
         * @return void
         */
        public function __construct()
        {
            if($this->isPostMethod())
                $this->handlePostRequest();
            else if($this->isPutMethod())
                $this->handlePutRequest();
            else if($this->isPatchMethod())
                $this->handlePatchRequest();
            else if($this->isDeleteMethod())
                $this->handleDeleteRequest();
            else
                $this->handleGetRequest();
        }

        /**
         * @return void
         */
        private final function handleGetRequest()
        {
            if(isset($_GET))
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
            if(isset($_POST))
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
            if(isset($_POST))
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
            if(isset($_POST))
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
            if(isset($_POST))
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
        public final function isGetMethod() : bool
        {
            if(strtoupper( $_SERVER['REQUEST_METHOD'] ) == "GET")
                return true;
            return false;
        }

        /**
         * @return boolean
         */
        public final function isPostMethod() : bool
        {
            if(strtoupper( $_SERVER['REQUEST_METHOD'] ) == "POST")
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
            if(strtoupper( $_SERVER['REQUEST_METHOD'] ) == "POST")
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
            if(strtoupper( $_SERVER['REQUEST_METHOD'] ) == "POST")
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
            if(strtoupper( $_SERVER['REQUEST_METHOD'] ) == "POST")
            {
                if(isset($_POST["POST"]) && $_POST["POST"] == "__DELETE")
                    return true;
                return false;
            }
            return false;
        }

        /**
         * @return \stdClass
         */
        public final function get() : stdClass
        {
            $anonymous = new class($this->getRequest) extends stdClass
            {
                private $getRequest;
                public function __construct(array $getRequest)
                {
                    $this->getRequest = $getRequest;
                }
                public function has($item) : bool
                {
                    return isInAssoc($this->getRequest, $item);
                }
                public function toArray() : array
                {
                    return $this->getRequest;
                }
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
         * @return \stdClass
         */
        public final function post() : stdClass
        {
            $anonymous = new class($this->postRequest) extends stdClass
            {
                private $postRequest;
                public function __construct(array $postRequest)
                {
                    $this->postRequest = $postRequest;
                }
                public function has($item) : bool
                {
                    return isInAssoc($this->postRequest, $item);
                }
                public function toArray() : array
                {
                    return $this->postRequest;
                }
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
         * @return \stdClass
         */
        public final function put() : stdClass
        {
            $anonymous = new class($this->putRequest) extends stdClass
            {
                private $putRequest;
                public function __construct(array $putRequest)
                {
                    $this->putRequest = $putRequest;
                }
                public function has($item) : bool
                {
                    return isInAssoc($this->putRequest, $item);
                }
                public function toArray() : array
                {
                    return $this->putRequest;
                }
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
         * @return \stdClass
         */
        public final function patch() : stdClass
        {
            $anonymous = new class($this->patchRequest) extends stdClass
            {
                private $patchRequest;
                public function __construct(array $patchRequest)
                {
                    $this->patchRequest = $patchRequest;
                }
                public function has($item) : bool
                {
                    return isInAssoc($this->patchRequest, $item);
                }
                public function toArray() : array
                {
                    return $this->patchRequest;
                }
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
         * @return \stdClass
         */
        public final function delete() : stdClass
        {
            $anonymous = new class($this->deleteRequest) extends stdClass
            {
                private $deleteRequest;
                public function __construct(array $deleteRequest)
                {
                    $this->deleteRequest = $deleteRequest;
                }
                public function has($item) : bool
                {
                    return isInAssoc($this->deleteRequest, $item);
                }
                public function toArray() : array
                {
                    return $this->deleteRequest;
                }
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