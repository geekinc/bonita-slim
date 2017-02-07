<?php

use Slim\Middleware\TokenAuthentication;

// Application middleware
// Authentication...
//$app->add(new \Slim\Middleware\HttpBasicAuthentication([
//    "path" => ["/api"],
//    "passthrough" => ["/api/doc", "/api/register", "/api/data", "/api/brightree", "/api/faxout"],
//    "realm" => "API Authentication",
//    "secure" => false,                                                  // Set to true for forced https
//    "users" => $app->getContainer()->get('settings')['admin_users'],
//    "error" => function ($request, $response, $arguments) {
//        $data = [];
//        $data["status"] = "error";
//        $data["message"] = $arguments["message"];
//        return $response->write(json_encode($data, JSON_UNESCAPED_SLASHES));
//    }
//]));

$authenticator = function($request, TokenAuthentication $tokenAuth){
    /**
     * Try find authorization token via header, parameters, cookie or attribute
     * If token not found, return response with status 401 (unauthorized)
     */

    $token = $tokenAuth->findToken($request);

    if ($token != 'usertokensecret') {
        /**
         * The throwable class must implement UnauthorizedExceptionInterface
         */
        throw new UnauthorizedException('Invalid Token');
    }
    $user = [
        'name' => 'Dyorg',
        'id' => 1,
        'permisssion' => 'admin'
    ];
    return $user;
};

/**
 * Add token authentication middleware
 */
$app->add(new TokenAuthentication([
    'path' =>   '/api/register',
    'secure' => false,
    'parameter' => 'token',
    'authenticator' => $authenticator
]));

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});