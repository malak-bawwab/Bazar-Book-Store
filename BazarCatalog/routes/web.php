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
    return $router->app->version();
});
$router->get('/search/{topic}', ['uses' => 'BooksController@showBasedOnTopic']);
$router->get('/lookup/{itemNumber}', ['uses' => 'BooksController@showBasedOnItemNumber']);

// to complete buy process,will be called from order service
//$router->get('/query/{itemNumber}', ['uses' => 'BooksController@checkIfExists']);

$router->put('/update/book/{itemNumber}/cost/{newCost}', ['uses' => 'BooksController@updateCost']);
//update quantity(set it to new Value,increase items,decrese items,decrement quantity by 1 in case it is called from order server through buy operation).
$router->put('/update/book/{itemNumber}/type/{type}/value/{value}', ['uses' => 'BooksController@updateAndNotify']);
//there is an update from other replicas
$router->get('/notify/{itemNumber}/{updateType}/{newValue}', ['uses' => 'BooksController@applyUpdates']);
