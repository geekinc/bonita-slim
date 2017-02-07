<?php

define("BASE_PATH", ($_SERVER['SERVER_ADDR'] === '192.168.33.40') ? "/api" : "/api");
define("BASE_SCHEMES", ($_SERVER['SERVER_ADDR'] === '192.168.33.40') ? "http":  "http");
define("AUTH_PATH", ($_SERVER['SERVER_ADDR'] === '192.168.33.40') ? "http://192.168.33.40/api/oauth/dialog":  "http://192.168.33.40/api/oauth/dialog");

/**
 * @SWG\Swagger(
 *     schemes={BASE_SCHEMES},
 *     basePath=BASE_PATH,
 *     @SWG\Info(
 *         version="1.1.0",
 *         title="Application API",
 *         description="This is the Application API - This can be extended in any way your app requires",
 *     )
 * )
 */

// Swagger documentation routes
$app->get('/api/doc', 'GEEK\Controller\API\DocController:apiDoc')->setName('GEEK.api.apiDoc');

// Automation routes
$app->get('/api/startProcess', 'GEEK\Controller\API\AutomationController:apiStartProcess')->setName('GEEK.api.apiStartProcess');
$app->post('/api/startProcessWithParameters', 'GEEK\Controller\API\AutomationController:apiStartProcessWithParameters')->setName('GEEK.api.apiStartProcessWithParameters');

// Data routes
$app->get('/api/data', 'GEEK\Controller\API\DataController:apiData')->setName('GEEK.api.apiData');
$app->get('/api/data/status', 'GEEK\Controller\API\DataController:apiDataStatus')->setName('GEEK.api.apiDataStatus');
$app->get('/api/data/spreadsheet', 'GEEK\Controller\API\DataController:apiDataSpreadsheet')->setName('GEEK.api.apiDataSpreadsheet');
$app->get('/api/data/database', 'GEEK\Controller\API\DataController:apiDataDatabase')->setName('GEEK.api.apiDataDatabase');

/**
 * @SWG\SecurityScheme(
 *   securityDefinition="api_key",
 *   type="apiKey",
 *   in="header",
 *   name="authorization"
 * )
 */
