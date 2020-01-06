<?php
use Kernel\Http\Request;
use Kernel\Database\Pecod;
use Kernel\Http\Route\HttpRequest;
use Kernel\Core\Classes\Interfaces\Http\Route;
use Kernel\Core\Classes\Interfaces\Http\Route\Route as IRoute;

/*___________________________________________________________
 |
 | You can define your paths in the form of [ gets ] here
 |___________________________________________________________
*/

HttpRequest::get('/')->controller("Home")
                     ->action("Index");