<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Psr\Container\ContainerInterface;


use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class AuthController
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        // Inject the container to have access to the settings
        $this->container = $container;
    }

    public function login(Request $request, Response $response)
    {
        $data = $request->getParsedBody();
        $username = $data['username'];
        $password = $data['password'];

        // Your authentication logic goes here...
        // For example:
        if ($username === 'admin' && $password === 'password') {
            // Generate and return the JWT token
            $token = $this->generateToken(
                [
                    'username' => $username, 
                    'profilo' => 
                        [
                            'nome' => 'Mario',
                            'cognome' => 'Rossi'
                        ],
                ]);
            return $response->withJson(['token' => $token]);
        } else {
            return $response->withStatus(401)->withJson(['error' => 'Invalid username or password']);
        }
    }

    protected function generateToken($data)
    {
        $secret = $this->container->get('config')['jwt']['secret'];
        $token = JWT::encode($data, $secret, 'HS256');
        return $token;
    }

    public function verify($token, Request $request, Response $response)
    {
        $secret = $this->container->get('config')['jwt']['secret'];

        try {
            $decoded = JWT::decode($token, new Key($secret, 'HS256'));
            return $response->withJson(['data' => (array) $decoded]);
        } catch (\Exception $e) {
            return $response->withStatus(401)->withJson(['error' => $e->getMessage()]);
        }
    }
}
