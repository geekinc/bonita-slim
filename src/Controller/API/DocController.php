<?php
namespace GEEK\Controller\API;

use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class DocController
 * @package GEEK\Controller
 *
 * NOTE:  Remember to uncomment this line in php.ini
 *        always_populate_raw_post_data = -1
 *
 */
class DocController extends \GEEK\Controller\AbstractController
{
    private $app;
    private $bonitaServer, $bonitaUser, $bonitaPassword;
    function __construct($app) {
        parent::__construct($app);
        $this->app = $app;
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
        @$swagger = \Swagger\scan($this->app['settings']['base_dir']);
        return $response->withJSON($swagger);
    }
}