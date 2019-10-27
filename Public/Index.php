<?php
include_once '../Vendor/autoload.php';
\Kernel\Http\Route\RouterRunner::run(explode(__DIR__, dirname(__DIR__))[0]);