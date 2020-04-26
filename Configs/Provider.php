<?php

return
[

    /*___________________________________________________________
     |
     | You can configure the middleware settings here
     |___________________________________________________________
    */

    'Middleware' =>
    [
        'Auth' => \Kernel\Http\Middleware\Auth::class,
        'Boot' => \Kernel\Http\Middleware\Boot::class,
        'CSRF' => \Kernel\Http\Middleware\CrossSiteRequestForgery::class
    ]

    ,

    /*___________________________________________________________
     |
     | You can configure the Compressor file settings here
     |___________________________________________________________
    */

    'Compressor' =>
    [
        "JavaScript" =>
        [

        ]

        ,

        "StyleSheet" =>
        [

        ]
    ]

];