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

 return view('greeting', ['result' => '']);

   

});

//to parse enterd commands from GUI
$router->post('/command', ['uses' => 'BooksController@parseCommands']);


$router->get('/invalidate/{itemNumber}', ['uses' => 'BooksController@invalidateData']);
$router->get('/search/topic/{topic}', ['uses' => 'BooksController@searchBasedOnTopic']);
$router->get('/lookup/number/{itemNumber}', ['uses' => 'BooksController@lookupBasedOnNumber']);
$router->post('/buy/number/{itemNumber}', ['uses' => 'BooksController@buyBasedOnNumber']);


