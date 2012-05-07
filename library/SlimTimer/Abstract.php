<?php

abstract class SlimTimer_Abstract
{
    protected $apiUrl = 'http://slimtimer.com';
    protected $apiKey;
    
    protected $userId;
    protected $userToken;
    
    protected $responseBody;
    
    
    public function setApiKey($string) { $this->apiKey = $string; }
    
    public function setUserId($id) { $this->userId = $id; }
    public function getUserId() { return $this->userId; }
    
    public function setUserToken($token) { $this->userToken = $token; }
    public function getUserToken() { return $this->userToken; }
    
    
    protected function makeRequest($url, $xml, $method)
    {
        $client = new Zend_Http_Client($url);
        $client->setHeaders(array(
            'Accept-encoding' => 'deflate',
            'Content-Type' => 'application/xml',
            'Accept' => 'application/xml')
        );
        $client->setRawData($xml, 'text/xml');
        $response = $client->request($method);
        
        return $response->getBody();
    }
    
    protected function parseResponse($response)
    {
        return simplexml_load_string($response);
    }
    
    protected function isRequestError($result)
    {
        if (isset($result->error)) {
            return true;
        }
    }
    
    protected function generateRequestXml()
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
         <request>
            <access-token>' . $this->userToken . '</access-token>
            <api-key>' . $this->apiKey . '</api-key>
         </request>';
         return simplexml_load_string($xml);
    }
}