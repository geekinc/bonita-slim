<?php
namespace GEEK\Controller;

use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class ApiController
 * @package GEEK\Controller
 *
 * NOTE:  Remember to uncomment this line in php.ini
 *        always_populate_raw_post_data = -1
 *
 */
class ApiController extends AbstractController
{
    private $app;
    function __construct($app) {
        parent::__construct($app);
        $this->app = $app;

        \Gengo\Config::setAPIkey($app->get('settings')['gengo']['api_key']);
        \Gengo\Config::setPrivateKey($app->get('settings')['gengo']['private_key']);
    }

    /**
     * @SWG\Get(
     *     path = "/doc",
     *     summary = "Returns the Swagger JSON definition file",
     *     tags = {"doc"},
     *     description = "Scans the PHP annotations and renders a complete description of the API for Swagger to render",
     *     operationId = "getDoc",
     *     produces = {"application/json"},
     *     @SWG\Response (
     *         response = "200",
     *         description = "Valid request",
     *     )
     * )
     */
    public function apiDoc(Request $request, Response $response, $args)
    {
        @$swagger = \Swagger\scan('/var/www/src');
        return $response->withJSON($swagger);
    }

}

