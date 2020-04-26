<?php

/**
 * @author  Hasan Karami
 * @version 1
 * @package CCore
 */
namespace Kernel\Http\WebServices
{
    class WebService
    {
        /**
         * @var string
         */
        public const HTTP_POST   = "POST";

        /**
         * @var string
         */
        public const HTTP_PUT    = "PUT";

        /**
         * @var string
         */
        public const HTTP_PATCH  = "PATCH";

        /**
         * @var string
         */
        public const HTTP_DELETE = "DELETE";

        /**
         * @var string $url
         */
        private string $url;

        /**
         * @var boolean $byHeader
         */
        private bool $byHeader = false;

        /**
         * @var array $headers
         */
        private array $headers;

        /**
         * @var string $method
         */
        private string $method = self::HTTP_POST;

        /**
         * @var string $jsonData
         */
        private string $jsonData;

        /**
         * @var array $formData
         */
        private array $formData;

        /**
         * @var boolean $byResponse
         */
        private bool $byResponse = false;

        /**
         * @var WebService $instance
         */
        private static WebService $instance;

        /**
         * @param  string $url
         * @return void
         */
        private function __construct(string $url)
        {
            $this->url = $url;
        }

        /**
         * @param  string $url
         * @return self
         */
        public static function start(string $url) : self
        {
            if(!isset(self::$instance)) self::$instance = new self($url);
            return self::$instance;
        }

        /**
         * @param  bool $key
         * @return self
         */
        public function byHeaderRequest(bool $key) : self
        {
            $this->byHeader = $key;
            return $this;
        }

        /**
         * @param  array $headers
         * @return self
         */
        public function setHeader(array $headers) : self
        {
            $this->headers = $headers;
            return $this;
        }

        /**
         * @param  string $method
         * @return self
         */
        public function setRestMethod(string $method) : self
        {
            $this->method = strtoupper($method);
            return $this;
        }

        /**
         * @param  string $json
         * @return self
         */
        public function setDataByJsonRequest(string $json) : self
        {
            $this->jsonData = $json;
            return $this;
        }

        /**
         * @param  array $data
         * @return self
         */
        public function setDataByFormRequest(array $data) : self
        {
            $this->formData = $data;
            return $this;
        }

        /**
         * @param  boolean $key
         * @return self
         */
        public function returnResponse(bool $key = true) : self
        {
            $this->byResponse = $key;
            return $this;
        }

        /**
         *
         */
        public function exec()
        {
            $handler = curl_init();
            curl_setopt($handler , CURLOPT_URL            , $this->url);
            curl_setopt($handler , CURLOPT_HEADER         , $this->byHeader);
            curl_setopt($handler , CURLOPT_CUSTOMREQUEST  , $this->method);
            curl_setopt($handler , CURLOPT_RETURNTRANSFER , $this->byResponse);

            if(isset($this->headers))
                curl_setopt($handler , CURLOPT_HTTPHEADER , $this->headers);

            if(isset($this->jsonData))
                curl_setopt($handler , CURLOPT_POSTFIELDS , $this->jsonData);

            if(isset($this->formData))
                curl_setopt($handler , CURLOPT_POSTFIELDS , http_build_query($this->formData));

            $result = curl_exec($handler);
            curl_close($handler);
            return $result;
        }
    }
}