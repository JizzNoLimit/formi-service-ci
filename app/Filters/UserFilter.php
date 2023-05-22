<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class UserFilter implements FilterInterface
{
    
    public function before(RequestInterface $request, $arguments = null)
    {
        $request = service("request");
        $response = service('response');

        $key = getenv('JWT_SECRET');
        $header = $request->getHeader("Authorization");
        $token = null;

        // extract the token from the header
        if (!empty($header)) {
            if (preg_match('/Bearer\s(\S+)/', $header, $matches)) {
                $token = $matches[1];
            }
        }

        // check if token is null or empty
        if (is_null($token) || empty($token)) {
            $response = service('response');
            $response->setBody('Access denied');
            $response->setStatusCode(401);
            return $response;
        }

        try {
            // $decoded = JWT::decode($token, $key, array("HS256"));
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            if ($decoded->role !== "mahasiswa") {
                $response = service('response');
                $response->setBody('Access denied');
                $response->setStatusCode(401);
                return $response;
            }
        } catch (Exception $ex) {
            $response->setBody('Access denied');
            $response->setStatusCode(401);
            return $response;
        }
    }

    
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
