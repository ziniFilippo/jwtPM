<?php
use Psr\Http\Message\ResponseInterface as Response;


$app->get('', function (Response $response) {
    $response->getBody()->write(file_get_contents('index.html'));
    return $response;
});

$app->get('login', function (Response $response) {
    //get the html file
    $response->getBody()->write(file_get_contents('login.html'));
    return $response;
});

$app->get('api/users/', [\App\Controllers\UserController::class,'getUsers']);
$app->get('api/users/{id}', [\App\Controllers\UserController::class,'getUser']);
$app->get('api/users/{id}/passwords', [\App\Controllers\UserController::class,'getAllPasswords']);
$app->get('api/users/{id}/passwords/{passwordId}', [\App\Controllers\UserController::class,'getPassword']);

$app->post('api/login', [\App\Controllers\AuthController::class, 'login']);

// Add a route to verify the token
$app->get('api/verify/{token}', [\App\Controllers\AuthController::class, 'verify']);