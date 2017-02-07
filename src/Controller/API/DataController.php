<?php
namespace GEEK\Controller\API;

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\PDO\Database;
use PHPExcel_IOFactory;

/**
 * Class DataController
 * @package GEEK\Controller
 *
 * NOTE:  Remember to uncomment this line in php.ini
 *        always_populate_raw_post_data = -1
 *
 */
class DataController extends \GEEK\Controller\AbstractController
{
    private $app;
    function __construct($app) {
        parent::__construct($app);
        $this->app = $app;
    }

    /**
     * @SWG\Get(
     *     path = "/data",
     *     summary = "Returns a simple data response",
     *     tags = {"data"},
     *     description = "Takes a simple array and returns it as a JSON object",
     *     operationId = "getData",
     *     produces = {"application/json"},
     *     @SWG\Response (
     *         response = "200",
     *         description = "Valid request",
     *     )
     * )
     */
    public function apiData(Request $request, Response $response, $args)
    {
        $data = ["paths" => [
            "/data/wip"
        ]];
        return $response->withJSON($data);
    }

    /**
     * @SWG\Get(
     *     path = "/data/status",
     *     summary = "Returns a list of potential statuses",
     *     tags = {"data"},
     *     description = "Returns a list of statuses",
     *     operationId = "getDataStatus",
     *     produces = {"application/json"},
     *     @SWG\Response (
     *         response = "200",
     *         description = "Valid request",
     *     )
     * )
     */
    public function apiDataStatus(Request $request, Response $response, $args)
    {
        $data = [
            "Available",
            "Pending",
            "Active",
            "Test",
            "Delivered",
            "Invalid",
            "Fixed",
            "Could Not Reproduce"
        ];
        return $response->withJSON($data);
    }

    /**
     * @SWG\Get(
     *     path = "/data/spreadsheet",
     *     summary = "Returns data from a spreadsheet",
     *     tags = {"data"},
     *     description = "Returns data from a spreadsheet",
     *     operationId = "getDataSpreadsheet",
     *     produces = {"application/json"},
     *     @SWG\Response (
     *         response = "200",
     *         description = "Valid request",
     *     )
     * )
     */
    public function apiDataSpreadsheet(Request $request, Response $response, $args)
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);

        $file_name =  __DIR__ . '/../../data/Spreadsheet.xlsx';
        $sheet_name = htmlentities("Sheet1");

        $objReader = PHPExcel_IOFactory::createReaderForFile($file_name);
        $objReader->setLoadSheetsOnly(array($sheet_name));
        $objReader->setReadDataOnly(true);
        $objPHPExcel = $objReader->load($file_name);
        $highestRow = $objPHPExcel->setActiveSheetIndex()->getHighestRow();
        $sheetData = $objPHPExcel->getSheet(0)->toArray(NULL, true, true, true);

        $data = [];
        for ($row = 1; $row <= $highestRow; ++ $row)
        {
            if (is_null($sheetData[$row]["A"]) &&
                is_null($sheetData[$row]["B"]))
            {
                // ignore row
            } else {
                $data[] = $sheetData[$row];

            }
        }

        return $response->withJSON($data);
    }

    /**
     * @SWG\Get(
     *     path = "/data/database",
     *     summary = "Returns data from a database",
     *     tags = {"data"},
     *     description = "Returns data from a database",
     *     operationId = "getDataDatabase",
     *     produces = {"application/json"},
     *     @SWG\Response (
     *         response = "200",
     *         description = "Valid request",
     *     )
     * )
     */
    public function apiDataDatabase(Request $request, Response $response, $args)
    {
        $dsn = 'mysql:host=' .
            $this->app['settings']['doctrine']['connection']['host'] .
            ';dbname=' .
            $this->app['settings']['doctrine']['connection']['dbname'] .
            ';charset=utf8';
        $usr = $this->app['settings']['doctrine']['connection']['user'];
        $pwd = $this->app['settings']['doctrine']['connection']['password'];

        $pdo = new Database($dsn, $usr, $pwd);

        // PDO structure
//        $selectStatement = $pdo->select()
//            ->from('user');
//        $stmt = $selectStatement->execute();
//        $data = $stmt->fetch();

        // Raw Query Structure
        $data = $pdo->query("select * from user")->fetchAll();

        return $response->withJSON($data);
    }

}