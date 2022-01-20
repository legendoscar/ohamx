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
        'API Documentation URL => https://documenter.getpostman.com/view/6959988/UVXerxZ3',
        'API Server URL => https://ohamx.herokuapp.com/'
    ];
});

$router->group(['prefix' => 'api'], function ($router) 
{
    /* BLOG POST */
    $router->get('blog', ['uses' => 'BlogPostsController@showAllPublishedAndActivePosts']);
    $router->post('blog', ['uses' => 'BlogPostsController@createBlogPost']);
    $router->get('blog/{id:[0-9]+}', ['uses' => 'BlogPostsController@showSingleBlogPost']);
    $router->put('blog/{id:[0-9]+}', ['uses' => 'BlogPostsController@updateSingleBlogPost']);
    $router->delete('blog/{id:[0-9]+}', ['uses' => 'BlogPostsController@deleteSingleBlogPost']);
    
    $router->get('blog/author/{id}', ['uses' => 'BlogPostsController@showAllBlogPostsByAuthor']);

    //status
    $router->get('blog/drafts', ['uses' => 'CryptoController@getRecommendedCrypto']);
    $router->get('crypto/new', ['uses' => 'CryptoController@getNewCrypto']);



});


