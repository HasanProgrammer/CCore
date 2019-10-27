<?php
/**
 * @author  Hasan Karami
 * @version 1
 * @package CCore
 */
namespace Kernel\Http\BankPort
{

    use Kernel\Core\Classes\Interfaces\Http\BankPort\Payment as BasePayment;

    final class Payment
    {
        private $refId;
        private $status;
        /**
         * @return void
         */
        public final function __construct()
        {
            $request = new \Kernel\Http\Request();
            if( $request->get()->Status == 'OK' )
            {
                $client = new \SoapClient(config('BankPort')['Zarinpal']['Url'], ['encoding' => 'UTF-8']);
                $result = $client->PaymentVerification([
                    'MerchantID' => config('BankPort')['Zarinpal']['MerchantID'] ,
                    'Authority'  => $request->get()->Authority                   ,
                    'Amount'     => $request->get()->Amount                      ,
                ]);
                $this->refId  = $result->RefID;
                $this->status = $result->Status;
            }
        }
        /**
         * @param  BasePayment $bankPortPayment
         * @return void
         */
        public final function onReadyRespons(BasePayment $bankPortPayment)
        {
            if( $this->status == 100 )
                $bankPortPayment->onSuccessRespons($this->refId, $this->status);
            else
                $bankPortPayment->onErrorRespons();
        }
    }
}