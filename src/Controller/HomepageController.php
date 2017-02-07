<?php
namespace GEEK\Controller;

use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class HomepageController
 * @package GEEK\Controller
 */
class HomepageController extends AbstractController
{
    private $app;
    function __construct($app) {
        parent::__construct($app);
        $this->app = $app;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     */
    public function hp(Request $request, Response $response, $args)
    {
        $this->app['logger']->info('Do your magic here.');
        $this->app['logger']->warning('Do your magic here.');
        $this->app['logger']->error('Do your magic here.');

        // $body = $this->view->fetch('website/pages/homepage.twig');
        // return $response->write($body);
        return $response->withRedirect('/apidoc');
    }
}

