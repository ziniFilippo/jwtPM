<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Controllers\AuthController;


// Add a route to say hello to a person
$app->get('hello/{name}', function ($name, Request $request, Response $response) {
    $response->getBody()->write('Hello ' . $name);
    return $response;
});


// Add routes

$app->get('', function (Request $request, Response $response) {
    $response->getBody()->write(file_get_contents('index.html'));
    return $response;
});

$app->get('login', function (Request $request, Response $response) {
    //get the html file
    $response->getBody()->write(file_get_contents('login.html'));
    return $response;
});


$app->post('api/login', [\App\Controllers\AuthController::class, 'login']);

// Add a route to verify the token
$app->get('api/verify/{token}', [\App\Controllers\AuthController::class, 'verify']);




