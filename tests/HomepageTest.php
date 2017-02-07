<?php
namespace COLONIAL\Tests;

require __DIR__ . '/../vendor/autoload.php';

use GuzzleHttp;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class HomepageTest
 * @package COLONIAL\Tests
 */
class HomepageTest extends \PHPUnit_Framework_TestCase
{
    /** @var  GuzzleHttp\Client */
    protected $client;

    public function setUp()
    {
        $this->client = new GuzzleHttp\Client([
            'base_uri' => 'http://localhost'
        ]);
    }

    public function testHomepageIsLoadedAndGives200()
    {
        $response = $this->client->request('GET', '/');
        $this->assertEquals(200, $response->getStatusCode());

        // Crawling for correct Hello World ;)
        /** @var Crawler $crawler */
        $crawler = new Crawler($response->getBody()->getContents());

//        $filter = $crawler->filter('h1');
//        $this->assertEquals('Hello, Slim 3!', $filter->text(), 'Error');
    }
}
