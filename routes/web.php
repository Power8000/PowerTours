<?php

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
$router->group(['prefix' => 'ruta' ], function($router){
    $router->get('/resultado', ['uses'=>'intranet\ruta\cRuta@getResponse']);
    $router->get('/preliminar', ['uses'=>'intranet\ruta\cRuta@getRoute']);
});
$router->group(['prefix' => 'origen' ], function($router){
    $router->get('/departamento', ['uses'=>'intranet\ruta\cRuta@getDepartmentOrigin']);
    $router->get('/lugar', ['uses'=>'intranet\ruta\cRuta@getPlacesOrigin']);
    // $router->get('/nuevo', ['uses'=>'intranet\user\cUser@restorePassword']);
});
$router->group(['prefix' => 'destino' ], function($router){
    $router->get('/departamento', ['uses'=>'intranet\ruta\cRuta@getDepartmentDestination']);
    $router->get('/lugar', ['uses'=>'intranet\ruta\cRuta@getPlacesDestination']);
    // $router->get('/nuevo', ['uses'=>'intranet\user\cUser@restorePassword']);
});
$router->post('/tipo_transporte', ['uses'=>'intranet\ruta\cRuta@getTypeOfTransport']);
$router->post('/categoria', ['uses'=>'intranet\ruta\cRuta@getCategoryTransport']);



$router->group(['prefix' => 'rutap' ], function($router){
    $router->post('/resultado', ['uses'=>'intranet\ruta\cRuta@getResponse']);
    $router->post('/preliminar', ['uses'=>'intranet\ruta\cRuta@getRoute']);
});
$router->group(['prefix' => 'origenp' ], function($router){
    $router->post('/departamento', ['uses'=>'intranet\ruta\cRuta@getDepartmentOrigin']);
    $router->post('/lugar', ['uses'=>'intranet\ruta\cRuta@getPlacesOrigin']);
    // $router->get('/nuevo', ['uses'=>'intranet\user\cUser@restorePassword']);
});
$router->group(['prefix' => 'destinop' ], function($router){
    $router->post('/departamento', ['uses'=>'intranet\ruta\cRuta@getDepartmentDestination']);
    $router->post('/lugar', ['uses'=>'intranet\ruta\cRuta@getPlacesDestination']);
    // $router->get('/nuevo', ['uses'=>'intranet\user\cUser@restorePassword']);
});
