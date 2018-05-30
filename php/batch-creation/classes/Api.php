<?php
namespace Linkfire;

require_once("HttpRequest.php");
require_once("BatchResponse.php");
require_once("TransactionInfo.php");
require_once("BoardResponse.php");
class Api {
    private $baseUrl = "https://api.linkfire.com";
    private $token;

    // Sets the bearer token
    public function setToken($token) {
        $this->token = $token;
    }

    // Calls API to create multiple campaign links based on csv, json or xml data data
    public function createCampaignLinks(string $boardId, string $data, string $format = "csv") : BatchResponse {
        try {
            $contentType = "";
            switch ($format) {
                case "csv":
                    $contentType = "text/csv";
                    break;
                case "json":
                    $contentType = "application/json";
                    break;
                case "xml":
                    $contentType = "application/xml";
                    break;
                default:
                    throw new \Exception("invalid format specified, must be one of csv, json or xml");
            }

            $url = $this->baseUrl."/campaigns/boards/".$boardId."/links/batch";

            $request = new HttpRequest();

            $result = $request->post($url, $data, $this->token, $contentType);

            $result = stripslashes($result);
            $jsonResult = json_decode($result, true);

            $response = new BatchResponse();

            $response->BatchId = $jsonResult["data"]["batchId"];
            $response->TransactionInfo = new TransactionInfo($jsonResult["transactionId"]);

            return $response;
        } catch (\Exception $ex) {
            throw new \Exception("Error creating campaign links", 1, $ex);
        }
    }

    public function getBoards() : array {
        $url = $this->baseUrl."/settings/boards";
        $request = new HttpRequest();
        $result = $request->get($url, $this->token);
        $result = stripslashes($result);
        $jsonResult = json_decode($result, true);
        $response = [];
        foreach ($jsonResult['data'] as $boardJson) {
            $board = new BoardResponse();
            $board->Id = $boardJson['id'];
            $board->Name = $boardJson['name'];
            $board->Domains = $boardJson['domains'];
            $response[] = $board;
        }
        return $response;
    }
}
