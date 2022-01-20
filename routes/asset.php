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
$router->group(['prefix' => 'api'], function ($router) 
{
    /* CRYPTO */
    $router->get('crypto', ['uses' => 'CryptoController@getAllCrypto']);
    $router->get('crypto/{id:[0-9]+}', ['uses' => 'CryptoController@showOneCrypto']);
    $router->post('crypto', ['uses' => 'CryptoController@createCryptoCoin']);
    $router->put('crypto/{id:[0-9]+}', ['uses' => 'CryptoController@updateCryptoCoin']);
    $router->delete('crypto/{id:[0-9]+}', ['uses' => 'CryptoController@deleteOneCrypto']);
    $router->get('crypto/popular', ['uses' => 'CryptoController@getPopularCrypto']);
    $router->get('crypto/recommended', ['uses' => 'CryptoController@getRecommendedCrypto']);
    $router->get('crypto/new', ['uses' => 'CryptoController@getNewCrypto']);
    $router->get('crypto/available', ['uses' => 'CryptoController@getAvailableCrypto']);
    $router->get('crypto/unavailable', ['uses' => 'CryptoController@getUnAvailableCrypto']);
    
    
    /* EWALLETS */
    $router->get('ewallets', ['uses' => 'EwalletsController@getAllEwallets']);
    $router->post('ewallets', ['uses' => 'EwalletsController@createEwallet']);
    $router->put('ewallets/{id:[0-9]+}', ['uses' => 'EwalletsController@updateEwallet']);
    $router->delete('ewallets/{id:[0-9]+}', ['uses' => 'EwalletsController@deleteOneEwallet']);
    $router->get('ewallets/popular', ['uses' => 'EwalletsController@getPopularEwallets']);
    $router->get('ewallets/recommended', ['uses' => 'EwalletsController@getRecommendedEwallets']);
    $router->get('ewallets/new', ['uses' => 'EwalletsController@getNewEwallets']);
    $router->get('ewallets/available', ['uses' => 'EwalletsController@getAvailableEwallets']);
    $router->get('ewallets/unavailable', ['uses' => 'EwalletsController@getUnAvailableEwallets']);
    
    
    /* EXCHANGE RATES */
    // SINGLE ASSET
    $router->get('rates/asset/{id:[0-9]+}', ['uses' => 'ExchangeRatesController@showExchangeRatesForAnAsset']);
    $router->delete('rates/asset/{id:[0-9]+}', ['uses' => 'ExchangeRatesController@deleteExchangeRatesForAnAsset']); //working on
    
    //SINGLE RATE
    $router->get('rates/id/{id:[0-9]+}', ['uses' => 'ExchangeRatesController@showSingleExchangeRate']);
    $router->post('rates', ['uses' => 'ExchangeRatesController@createAssetExchangeRate']);
    $router->put('rates/id/{id:[0-9]+}', ['uses' => 'ExchangeRatesController@updateAssetExchangeRate']);
    $router->delete('rates/id/{id:[0-9]+}', ['uses' => 'ExchangeRatesController@deleteExchangeRate']);
});


