<?php
/*
This example shows how to create a batch of linkfire campaigns
*/


require_once("classes/BatchRequest.php");

// Linkfire board id to create compaign links on
$boardId = "4f5322d1-21d3-4045-9daf-cd00a455a845";

// csv data in format specified on api documentation, omitting empty columns
$csvData = 
"BaseUrl, UPC, ISRC\n
\"https://open.spotify.com/track/5BSvMOjhomXGMXXUSDGxvU\",,
,602557317718,
,,SEWDL6001400
";

// Do authentication here, the api is using bearer tokens, see documentation on https://dev.linkfire.com, just doing dummy token as example
$token = "JWTTOKEN";

// Initialize the linkfire API
$api = new \Linkfire\Api();

// Set the token
$api->SetToken($token);

// Create campaign links
$response = $api->CreateCampaignLinks($boardId, $csvData, 'csv');

// BatchId of response contains id that can be used to get status of creation
var_dump($response);