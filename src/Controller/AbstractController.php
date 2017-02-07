<?php
namespace GEEK\Controller;

use Slim\Container;
use Slim\Views\Twig as TwigViews;

/**
 * Class AbstractController
 * @package GEEK\Controller
 */
abstract class AbstractController
{
    /** @var TwigViews view */
    protected $view;

    /**
     * AbstractController constructor.
     * @param Container $c
     */
    public function __construct(Container $c)
    {
        $this->view = $c->get('view');
    }
}
