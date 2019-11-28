<?php
/**
 * @author  Hasan Karami
 * @version 1
 * @package CCore
 */
namespace Kernel\Http\BankPort
{

    use Kernel\Core\Classes\Interfaces\Http\BankPort\Request as BaseRequest;

    final class Request
    {
        private $status;
        private $authority;
        /**
         * @param  integer $amount
         * @return void
         */
        public final function __construct(int $amount)
        {
            $client = new \SoapClient(config('BankPort')['Zarinpal']['Url'], ['encoding' => 'UTF-8']);
            $result = $client->PaymentRequest
            (
                [
                    'MerchantID'  => config('BankPort')['Zarinpal']['MerchantID']  ,
                    'Amount'      => $amount                                       ,
                    'Description' => config('BankPort')['Zarinpal']['Description'] ,
                    'Email'       => config('BankPort')['Zarinpal']['Email']       ,
                    'Mobile'      => config('BankPort')['Zarinpal']['Mobile']      ,
                    'CallbackURL' => config('BankPort')['Zarinpal']['CallbackURL']
                ]
            );
            $this->status    = $result->Status;
            $this->authority = $result->Authority;
        }
        /**
         * @param  BaseRequest $bankPortRequest
         * @return void
         */
        public final function onReadyRespons(BaseRequest $bankPortRequest)
        {
            if( $this->status == 100 )
                $bankPortRequest->onSuccessRespons($this->authority, $this->status);
            else
                $bankPortRequest->onErrorRespons($this->authority, $this->status);
        }
    }
}