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
        'Auth' => \Kernel\Http\Gate\Auth::class,
        'Boot' => \Kernel\Http\Gate\Boot::class,
        'CSRF' => \Kernel\Http\Gate\CrossSiteRequestForgery::class
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