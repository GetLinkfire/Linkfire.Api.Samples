<?php
namespace Linkfire;

require_once("BatchResponse.php");
require_once("TransactionInfo.php");
class Api {
    private $_baseUrl = "https://api.linkfire.com/";
    private $_token;

    // Sets the bearer token
    public function SetToken($token) {
        $this->_token = $token;
    }

    // Calls API to create multiple campaign links based on csv, json or xml data data
    public function CreateCampaignLinks($boardId, $data, $format = "csv") {
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

            $url = $this->_baseUrl."campaigns/boards/".$boardId."/links/batch";
            $ch = curl_init();
            //set the url, number of POST vars, POST data
            curl_setopt($ch,CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Authorization: Bearer '.$this->_token,
                'Content-Type: '.$contentType
            ));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);  //Post Fields
            //execute post
            $result = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            //close connection
            curl_close($ch);
            
            $status = $this->ParseHttpCode($httpCode);

            if ($status != "Success") {
                throw new \Exception($status." (".$httpCode.") posting to create batch endpoint:".$url."\n".$result);
            }
            $result = stripslashes($result);
            $jsonResult = json_decode($result, true);

            $response = new BatchResponse();

            $response->BatchId = $jsonResult["data"]["batchId"];
            $response->TransactionInfo = new \Linkfire\TransactionInfo($jsonResult["transactionId"]);

            return $response;
        } catch (\Exception $ex) {
            throw new \Exception("Error creating campaign links", 1, $ex);
        }
    }

    private function ParseHttpCode($httpCode) {
        if ($httpCode >= 200 && $httpCode <= 299) {
            return "Success";
        }
        if ($httpCode >= 400 && $httpCode <= 499) {
            return "ClientError";
        }
        if ($httpCode >= 500 && $httpCode <= 599) {
            return "ServerError";
        }
    }
}
