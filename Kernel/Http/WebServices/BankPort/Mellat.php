<?php

/**
 * @author  Hasan Karami
 * @version 1
 * @package CCore
 */
namespace Kernel\Http\WebServices\BankPort
{

    use stdClass;
    use SoapClient;

    final class Mellat
    {
        /**
         * @var integer
         */
        private $terminal_id;

        /**
         * @var string
         */
        private $username;

        /**
         * @var string
         */
        private $password;

        /**
         * @var integer
         */
        private $amount;

        /**
         * @var integer
         */
        private $order_id;

        /**
         * @param  integer $amount
         * @param  integer $order_id
         * @return void
         */
        public function __construct(int $amount, int $order_id)
        {
            $this->terminal_id = config("BankPort")["Mellat"]["TerminalID"];
            $this->username    = config("BankPort")["Mellat"]["Username"];
            $this->password    = config("BankPort")["Mellat"]["Password"];
            $this->amount      = $amount;
            $this->order_id    = $order_id;
        }

        /**
         * @param  string   $data
         * @param  callable $callBackError
         * @param  callable $callBackSuccess
         * @return void
         */
        public function payment(string $data = null, callable $callBackError, callable $callBackSuccess) : void
        {
            $target_data =
            [
                "terminalId"     => $this->terminal_id                          ,
                "userName"       => $this->username                             ,
                "userPassword"   => $this->password                             ,
                "orderId"        => $this->order_id                             ,
                "amount"         => $this->amount                               ,
                "localDate"      => date("Ymd")                                 ,
                "localTime"      => date("Gis")                                 ,
                "additionalData" => $data                                       ,
                "callBackUrl"    => config("BankPort")["Mellat"]["CallBackURL"] ,
                "payerId"        => "0"                                         ,
            ];
            $service = new SoapClient( config("Mellat")["RemoteURL-API"] );
            $result  = $service->call("bpPayRequest", $target_data, config("Mellat")["NameSpace"]);
            if($result->fault)
            {
                echo config("Message")["Bank"]["Error-Object"];
                exit();
            }
            $errors = $service->getError();
            if($errors)
            {
                call_user_func($callBackError, $errors);
                exit();
            }
            $new_result  = explode(",", $result);
            $result_code = $new_result[0];
            if($result_code == "0")
            {
                call_user_func($callBackSuccess, $new_result[1]);
                exit();
            }
        }

        /**
         * @param  callable $callBackSuccess
         * @return void
         */
        public function verify(callable $callBackSuccess) : void
        {
            if($_POST["ResCode"] == "0")
            {
                $target_data =
                [
                    "terminalId"            => $this->terminal_id        ,
                    "userName"              => $this->username           ,
                    "userPassword"          => $this->password           ,
                    "orderId"               => $_POST["SaleOrderId"]     ,
                    "verifySaleOrderId"     => $_POST["SaleOrderId"]     ,
                    "verifySaleReferenceId" => $_POST["SaleReferenceId"] ,
                ];
                $service = new SoapClient( config("Mellat")["RemoteURL-API"] );
                $result  = $service->call("bpVerifyRequest", $target_data, config("Mellat")["NameSpace"]);
                if($result == "0")
                {
                    $result = $service->call("bpSettleRequest", $target_data, config("Mellat")["NameSpace"]);
                    if($result == "0")
                    {
                        $information = new stdClass();
                        $information->orderId     = $_POST["SaleOrderId"];
                        $information->referenceId = $_POST["SaleReferenceId"];
                        call_user_func($callBackSuccess, $information);
                    }
                    $service->call("bpReversalRequest", $target_data, config("Mellat")["NameSpace"]);
                    exit();
                }
                $service->call("bpReversalRequest", $target_data, config("Mellat")["NameSpace"]);
                exit();
            }
        }
    }
}