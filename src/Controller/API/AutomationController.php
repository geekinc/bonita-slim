<?php
namespace GEEK\Controller\API;

use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class AutomationController
 * @package GEEK\Controller
 *
 * NOTE:  Remember to uncomment this line in php.ini
 *        always_populate_raw_post_data = -1
 *
 */
class AutomationController extends \GEEK\Controller\AbstractController
{
    private $app;
    private $bonitaServer, $bonitaUser, $bonitaPassword;
    function __construct($app) {
        parent::__construct($app);
        $this->app = $app;

        $this->bonitaServer = $app['settings']['bonita']['server'];
        $this->bonitaUser = $app['settings']['bonita']['user'];
        $this->bonitaPassword = $app['settings']['bonita']['password'];
    }

    public function startBonitaProcessWithVariables($userName, $password, $server, $processName, $variables)
    {
        // Authenticate with the REST engine
        $service_url = $server . '/bonita/loginservice';
        $curl = curl_init($service_url);
        $curl_post_data = 'username=' . $userName . '&password=' . $password . '&redirect=false';
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_post_data);
        curl_setopt($curl, CURLOPT_COOKIESESSION, true);
        curl_setopt($curl, CURLOPT_COOKIEJAR, 'cookie-name');  //could be empty, but cause problems on some hosts
        curl_setopt($curl, CURLOPT_COOKIEFILE, dirname(__FILE__) . "/cookies.txt");
        $curl_response = curl_exec($curl);
        $reponseInfo = curl_getinfo($curl);
        if ($curl_response === false) {
            $info = curl_getinfo($curl);
            curl_close($curl);
            die('1 error occurred during curl exec. Additional info: ' . var_export($info));
        }

        // Get the list of processes
        $service_url = $server . '/bonita/API/bpm/process?p=0';
        curl_setopt($curl, CURLOPT_URL, $service_url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($curl, CURLOPT_POST, false);
        //curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postData));
        $curl_response = curl_exec($curl);
        $reponseInfo = curl_getinfo($curl);
        if ($curl_response === false) {
            $info = curl_getinfo($curl);
            curl_close($curl);
            die('2 error occurred during curl exec. Additional info: ' . var_export($info));
        }

        // Parse response for process with the proper name
        $processes = json_decode($curl_response);
        $processID = 0;
        foreach ($processes as $process) {
            echo $process->displayName;
            if ($process->displayName == $processName) {
                $processID = $process->id;
            }
        }

        // Instantiate a new process case
        $service_url = $server . '/bonita/API/bpm/case/' . $processID;
        curl_setopt($curl, CURLOPT_URL, $service_url);
        $postData = array(
            'processDefinitionId' => $processID,
            'variables' => $variables
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postData));
        $curl_response = curl_exec($curl);
        $reponseInfo = curl_getinfo($curl);
        if ($curl_response === false) {
            $info = curl_getinfo($curl);
            curl_close($curl);
            die('3 error occurred during curl exec. Additional info: ' . var_export($info));
        }

        curl_close($curl);
        $decoded = json_decode($curl_response);
        if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
            die('4 error occurred: ' . $decoded->response->errormessage);
        }
        return $decoded;
    }

    /**
     * @SWG\Get(
     *     path = "/startProcess",
     *     summary = "Kicks off a Bonita process",
     *     tags = {"automation"},
     *     description = "Calls the BonitaSoft API to start a process on the server",
     *     operationId = "getDoc",
     *     produces = {"application/json"},
     *     @SWG\Response (
     *         response = "200",
     *         description = "Valid request",
     *     )
     * )
     */
    public function apiStartProcess(Request $request, Response $response, $args)
    {
        $processName = 'SimpleDataProcess';
        $variables = [
            ["name" => "stringData", "value" => "This is a test"]
        ];

        $decoded = $this->startBonitaProcessWithVariables(
            $this->bonitaUser,
            $this->bonitaPassword,
            $this->bonitaServer,
            $processName,
            $variables
            );

        return $response->withJSON($decoded);
    }

    /**
     * @SWG\Post(
     *     path = "/startProcessWithParameters",
     *     summary = "Provides a process kickoff endpoint.",
     *     tags = {"automation"},
     *     description = "Allow the current user to execute the process",
     *     operationId = "sessionRegister",
     *     consumes = {"application/json"},
     *     produces = {"application/json"},
     *     @SWG\Parameter (
     *         in = "body",
     *         name = "details",
     *         description = "Key value list for variables in process",
     *         required = true
     *     ),
     *     @SWG\Parameter (
     *         in = "query",
     *         name = "process",
     *         description = "Process Name",
     *         required = true
     *     ),
     *     @SWG\Parameter (
     *         in = "query",
     *         name = "token",
     *         description = "Authentication token",
     *         required = true
     *     ),
     *     @SWG\Response (
     *      response = "200",
     *      description = "The generated jwt token from the created user"
     *     )
     * )
     */
    public function apiStartProcessWithParameters(Request $request, Response $response, $args) {
        $parsedBody = $request->getParsedBody();
        $processName = $request->getQueryParam('process');
        $token = $request->getQueryParam('token');

        // Validate the request security token
        if ($token !== $this->app['settings']['security_token']) {
            return $response->withJson(['error' => 'Invalid Security Token']);
        }

        // Parse out the variables to pass to the process
        $variables = [];
        foreach ($parsedBody as $key => $value) {
            $variables[] = ['name' => $key, 'value' => $value];
        }

        $decoded = $this->startBonitaProcessWithVariables(
            $this->bonitaUser,
            $this->bonitaPassword,
            $this->bonitaServer,
            $processName,
            $variables
        );

        return $response->withJSON($decoded);
    }

}