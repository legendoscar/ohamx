<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
return [
        'API Documentation URL => https://documenter.getpostman.com/view/6959988/UVJeFFxk',
        'API Server URL => https://ohamx.herokuapp.com/'
    ];
});
