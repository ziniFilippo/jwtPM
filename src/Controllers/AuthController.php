<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
//use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Slim\Http\Response;



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
        // Check if the username and password are correct on the database
        
        //mysql connection
        $db = new \PDO('mysql:host=localhost;dbname=PM', 'root', '');

        $stmt = $db->prepare("SELECT MAIL, SHA3, salt FROM ACCOUNT WHERE MAIL = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();

        if (!$user) {
            return $response->withStatus(401)->withJson(['error' => 'Invalid username or password']);
        }else{
            $salt = $user['salt'];
            $hash = md5($password . $salt);
            if ($hash !== $user['SHA3']) {
                return $response->withStatus(401)->withJson(['error' => 'Invalid username or password']);
            }else{
                // Generate and return the JWT token
                $token = $this->generateToken(
                    [
                        'username' => $username, 
                        'profilo' => 
                            [
                                'nome' => $user['MAIL'],
                            ],
                    ]);
                    setcookie('jwt', $token, time() + 3600, '/', '', false, true);
                return $response->withJson(['token' => $token]);
            }
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