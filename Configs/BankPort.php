<?php

return
[

    /*___________________________________________________________
     |
     | You can configure the bank port settings here
     |___________________________________________________________
    */

    'Zarinpal' =>
    [
        'Url'          => "https://sandbox.zarinpal.com/pg/services/WebGate/wsdl" ,
        'MerchantID'   => "XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX"                  ,
        'Description'  => "Zarinpal"                                              ,
        'Email'        => ""                                                      ,
        'Mobile'       => ""                                                      ,
        'CallbackURL'  => getBankPortResponseUrl()
    ]

    ,

    "Mellat" =>
    [
        /* Personal */
        "TerminalID"  => "",
        "Username"    => "",
        "Password"    => "",

        /* Global */
        "RemoteURL-API" => "",
        "CallBackURL"   => "",
        "NameSpace"     => "",
        "BankPortURL"   => ""
    ]

];