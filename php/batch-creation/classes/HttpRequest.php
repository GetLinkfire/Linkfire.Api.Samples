<?php
namespace Linkfire;

class HttpRequest {
    public function post(string $url, string $postContent, ?string $token, string $contentType) : string {
        $headers = [
            'Content-Type: '.$contentType,
        ];
        if ($token !== null) {
            $headers[] = 'Authorization: Bearer '.$token;
        }
        $ch = curl_init();
        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postContent);  //Post Fields
        //execute post
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        //close connection
        curl_close($ch);
        
        $status = $this->ParseHttpCode($httpCode);

        if ($status != "Success") {
            throw new \Exception($status." (".$httpCode.") posting to url: ".$url."\n".$result);
        }
        return $result;
    }

    public function get(string $url, string $token) : string {
        $headers = [
            'Authorization: Bearer '.$token,
        ];
        $ch = curl_init();
        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        //execute post
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        //close connection
        curl_close($ch);
        
        $status = $this->ParseHttpCode($httpCode);

        if ($status != "Success") {
            throw new \Exception($status." (".$httpCode.") getting url: ".$url."\n".$result);
        }
        return $result;
    }

    private function ParseHttpCode(int $httpCode) : string {
        if ($httpCode >= 200 && $httpCode <= 299) {
            return "Success";
        }
        if ($httpCode >= 400 && $httpCode <= 499) {
            return "ClientError";
        }
        if ($httpCode >= 500 && $httpCode <= 599) {
            return "ServerError";
        }
        return "InvalidStatusCode";
    }
}