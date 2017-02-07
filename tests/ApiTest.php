<?php
namespace COLONIAL\Tests;

require __DIR__ . '/../vendor/autoload.php';

use GuzzleHttp;

/**
 * Class ApiTest
 * @package COLONIAL\Tests
 */
class ApiTest extends \PHPUnit_Framework_TestCase
{
    /** @var  GuzzleHttp\Client */
    protected $client;

    public function setUp()
    {
        $this->client = new GuzzleHttp\Client([
            'base_uri' => 'http://localhost'
        ]);
    }

    public function testApiIsLoadedAndGives200()
    {
        $data = $this->makeGetRequestCheckStatusAndReturnData("/api");
        $this->assertEquals($data['thisis'], "an api response", 'API is broken!');
    }

    public function testApiDocs()
    {
        $data = $this->makeGetRequestCheckStatusAndReturnData("/api/doc");
        $this->assertEquals($data['swagger'], "2.0", 'API is broken!');
    }

    /**
     * Making request, checking status and returning data as array
     * @param $path
     * @return array
     */
    private function makeGetRequestCheckStatusAndReturnData($path)
    {
        $response = $this->client->get($path, [
            'auth' => [
                'admin',
                'password'
            ]
        ]);
        $this->assertContains('application/json', $response->getHeader('content-type')[0], 'Not a JSON response');
        $this->assertEquals(200, $response->getStatusCode());
        return json_decode($response->getBody(), true);
    }
}
