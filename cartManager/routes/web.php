<?php

use App\Http\Controllers\CartController;
use App\Models\Cart;


$router->get('/', function () use ($router) {
    return $router->app->version();
});



$router->group(['prefix'=>'/cart'], function() use($router){

    $router->get('/', 'CartController@getCart');
    $router->post('/add/{item}', 'CartController@create');
    $router->delete('/decrease/{item}', 'CartController@decrease');
    $router->delete('/remove/{item}', 'CartController@remove');

});