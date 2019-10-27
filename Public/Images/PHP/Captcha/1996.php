<?php

include '../../../../Libs/Server/Classes/Captcha.php';
include '../../../../Libs/Server/Classes/Session.php';

(new \Libs\Server\Classes\Captcha(6))->setImageFont(10)
                                     ->setImageWidth(30)
                                     ->setImageHeight(30)
                                     ->create();