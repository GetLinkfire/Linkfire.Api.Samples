<?php
namespace Linkfire;

require_once("HttpRequest.php");
class Auth {
    private $_baseUrl = 'https://auth.linkfire.com/identity';

    public function getToken(string $clientId, string $clientSecret) {
        try {
            $url = $this->_baseUrl . '/connect/token';
            $data = "grant_type=client_credentials&client_id=$clientId&client_secret=$clientSecret&scope=public.api";
            $request = new HttpRequest();
            $response = $request->post($url, $data, null, 'application/x-www-form-urlencoded');
            $jsonResult = json_decode($response, false);
            return $jsonResult->access_token;
        } catch (\Exception $ex) {
            throw new \Exception("Authentication failed", 1, $ex);
        }
    }
}