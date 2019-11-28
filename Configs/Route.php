<?php

return
[

    /*___________________________________________________________
     |
     | You can configure the route settings here
     |___________________________________________________________
    */

    'Get' =>
    [
        'Error'         => "ErrorController.Index"        ,
        'Sign_In'       => "SignInController.Index"       ,
        'Sign_Up'       => "SignUpController.Index"       ,
        'User'          => "UserController.Index"         ,
        'Admin'         => "AdminController.Index"        ,
        'Repair'        => "RepairController.Index"       ,
        'Cart'          => "CartController.Index"         ,
        'Cart/Request'  => "CartController.Request"       ,
        'Cart/Response' => "CartController.Response"      ,
    ]

    ,

    'Post' =>
    [
        'Sign_In' => "SignInController.SignIn" ,
        'Sign_Up' => "SignUpController.SignUp" ,
        'Test'    => "TestController.Index"    ,
    ]

];