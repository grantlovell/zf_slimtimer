<?php

abstract class SlimTimer_Abstract
{
    protected $apiUrl = 'http://slimtimer.com';
    protected $apiXml;
    protected $apiKey;
    
    protected $userId;
    protected $userToken;
    
    protected $timeout = 60;
    protected $responseBody;
    
    public function setApiKey($value) 
    {
        if (!Zend_Validate::is($value, 'Alnum')) {
            throw new InvalidArgumentException("{$value} is not a valid API key. API keys must only be letters and numbers.");
        }
        $this->apiKey = $value; 
    }
    
    public function setUserId($value) 
    {
        if (!Zend_Validate::is($value, 'Digits')) {
            throw new InvalidArgumentException("{$value} is not a valid user id. User ids must be an integer.");
        }
        $this->userId = (int) $value; 
    }
    
    public function getUserId() { return $this->userId; }
    
    public function setUserToken($value) 
    {
        if (!Zend_Validate::is($value, 'Alnum')) {
            throw new InvalidArgumentException("{$value} is not a valid access token. Access tokens must only be letters and numbers.");
        }
        $this->userToken = $value; 
    }
    
    public function getUserToken() { return $this->userToken; }
    
    public function setTimeout($value) 
    {
        if (!Zend_Validate::is($value, 'Digits')) {
            throw new InvalidArgumentException("{$value} is not a valid timeout. Timeout must be an integer.");
        }
        $this->timeout = (int) $value; 
    }
    
    protected function makeRequest($url, $xml, $method)
    {
        $client = new Zend_Http_Client($url, array('timeout' => $this->timeout));
        $client->setHeaders(array(
            'Accept-encoding' => 'deflate',
            'Content-Type' => 'application/xml',
            'Accept' => 'application/xml')
        );
        $client->setRawData($xml, 'text/xml');
        $response = $client->request($method);
        $headers = $response->getHeaders();
        $body = $response->getBody(); 
        $output = $this->parseResponse($body);
        
        if ($headers['Status'] !== '200 OK') {
            $requestError = $this->getRequestError($output);
            throw new Zend_Exception("Processing request failed. {$requestError}");
        }
        
        return $output;
    }
    
    protected function parseResponse($response)
    {
        return simplexml_load_string($response);
    }
    
    protected function getRequestError($result)
    {
        if (isset($result->error)) {
            return (string) $result->error;
        }
        return false;
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