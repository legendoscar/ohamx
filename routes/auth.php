<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for authentication.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
use App\Http\Controllers;

// $router->get('/', function () use ($router) {
//     return $router->app->version();
// });

$router->group(['prefix' => 'api'], function ($router) 
{
    $router->get('profile', ['uses' => 'Auth\AuthController@getAuthenticatedUser']);
});
 

$router->group(['prefix' => 'api/auth'], function () use ($router) {

    
    $router->post('register/user',  ['uses' => 'Auth\AuthController@registerUser']);
    $router->post('register/admin',  ['uses' => 'Auth\AuthController@registerUser']);
    

    $router->post('login',  ['uses' => 'Auth\AuthController@login']);
    $router->post('logout',  ['uses' => 'Auth\AuthController@logout']); 



    // $router->post('register/user',  ['uses' => 'Auth\AuthController@register']);
    // $router->post('login/user',  ['uses' => 'Auth\AuthController@login']);
    // $router->get('logout/user',  ['uses' => 'Auth\AuthController@logout']);

});