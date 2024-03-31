<?php

namespace App\Controllers;

use Psr\Container\ContainerInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class UserController {
    private $db;
    protected $container;

    public function __construct(ContainerInterface $container) {
        $this->db =  new \PDO('mysql:host=localhost;dbname=PM', 'root', '');
        $this->container = $container;

    }
    public function verify($request, $response) {
        if ($request->getCookieParam('jwt') == null) {
            return false;
        }
        $token = $request->getCookieParam('jwt');
        $secret = $this->container->get('config')['jwt']['secret'];
        try {
            $decoded = JWT::decode($token, new Key($secret, 'HS256'));
            if($response->withJson(['data' => (array) $decoded]))
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    // Get all users
    public function getUsers($request,$response) {
        if (!$this->verify($request, $response)) {
            return $response->withStatus(401)->withJson(['error' => 'Unauthorized']);
        }
        $stmt = $this->db->prepare("SELECT * FROM ACCOUNT");
        $stmt->execute();
        $users = $stmt->fetchAll();
        return $response->withJson($users);
    }

    // Get a user by id
    public function getUser($request,$response) {
        if (!$this->verify($request, $response)) {
            return $response->withStatus(401)->withJson(['error' => 'Unauthorized']);
        }
        $id =  $request->getAttribute('id');
        $stmt = $this->db->prepare("SELECT * FROM ACCOUNT WHERE ID = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $user = $stmt->fetch();
        return $response->withJson($user);
    }

    //get all passwords for a user
    public function getAllPasswords($request,$response) {
        if (!$this->verify($request, $response)) {
            return $response->withStatus(401)->withJson(['error' => 'Unauthorized']);
        }
        $id =  $request->getAttribute('id');
        $stmt = $this->db->prepare("SELECT * FROM CREDENZIALE WHERE ACCOUNT_ID = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $passwords = $stmt->fetchAll();
        return $response->withJson($passwords);
    }

    //get a password for a user
    public function getPassword($request,$response) {
        if (!$this->verify($request, $response)) {
            return $response->withStatus(401)->withJson(['error' => 'Unauthorized']);
        }
        $id =  $request->getAttribute('id');
        $passwordId =  $request->getAttribute('passwordId');
        $stmt = $this->db->prepare("SELECT * FROM CREDENZIALE WHERE ACCOUNT_ID= :id AND ID = :passwordId");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':passwordId', $passwordId);
        $stmt->execute();
        $password = $stmt->fetch();
        return $response->withJson($password);
    }
}