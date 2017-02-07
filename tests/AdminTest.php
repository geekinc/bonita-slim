<?php
namespace COLONIAL\Tests;

require __DIR__ . '/../vendor/autoload.php';

use GuzzleHttp;

/**
 * Class AdminTest
 * @package COLONIAL\Tests
 */
class AdminTest extends \PHPUnit_Framework_TestCase
{
    /** @var  GuzzleHttp\Client */
    protected $client;

    public function setUp()
    {
        $this->client = new GuzzleHttp\Client([
            'base_uri' => 'http://localhost'
        ]);
    }

    public function testAdminLogin()
    {
        $response = $this->client->get('/admin', [
            'auth' => [
                'admin',
                'password'
            ]
        ]);
        $this->assertContains('text/html', $response->getHeader('content-type')[0], 'Not an HTML response');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testAdminLogout()
    {
        $response = $this->client->get('/admin/logout', [
            'http_errors' => false
        ]);
        $this->assertEquals(401, $response->getStatusCode());
    }
}
