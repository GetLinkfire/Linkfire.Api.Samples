<?php
/*
This example shows how to create a batch of linkfire campaigns
*/


require_once("classes/Auth.php");
require_once("classes/Api.php");

// csv data in format specified on api documentation, omitting empty columns
$csvData = 
"BaseUrl, UPC, ISRC\n
\"https://open.spotify.com/track/5BSvMOjhomXGMXXUSDGxvU\",,
,602557317718,
,,SEWDL6001400
";

// Get token from authentication service
$auth = new \Linkfire\Auth();
$token = $auth->getToken('clientid','clientsecret');


// Initialize the linkfire API
$api = new \Linkfire\Api();

// Set the token
$api->setToken($token);

// Get the first available board
$boardId = $api->getBoards()[0]->Id;

// Create campaign links
$response = $api->createCampaignLinks($boardId, $csvData, 'csv');

// BatchId of response contains id that can be used to get status of creation
var_dump($response);