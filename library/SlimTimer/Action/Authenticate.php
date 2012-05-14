<?php

class SlimTimer_Action_Authenticate extends SlimTimer_Abstract
{
    protected $apiPath = '/users/token'; 
    protected $email;
    protected $password;
    
    public function setEmail($value) 
    {
        if (!Zend_Validate::is($value, 'EmailAddress')) {
            throw new InvalidArgumentException("{$value} is not an a valid email address.");
        }
        $this->email = $value; 
    }
    
    public function setPassword($password) { $this->password = $password; }
    
    public function run()
    {
        $url = $this->apiUrl . $this->apiPath;
        $xml = $this->buildRequestXml();
        $result = $this->makeRequest($url, $xml, 'POST');
        
        $this->userId = (string) $result->{'user-id'};
        $this->userToken = (string) $result->{'access-token'};
    }
    
    protected function buildRequestXml()
    {
        if (!isset($this->apiKey)) {
            throw new Zend_Exception("Processing request failed. <em>api_key</em> must be set.");
        }
        if (!isset($this->email)) {
            throw new Zend_Exception("Processing request failed. <em>email</em> must be set.");
        }
        if (!isset($this->password)) {
            throw new Zend_Exception("Processing request failed. <em>password</em> must be set.");
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>
         <request>
           <user>
             <email>' . $this->email . '</email>
             <password>' . $this->password . '</password>
           </user>
           <api-key>' . $this->apiKey . '</api-key>
         </request>';
        return $xml;
    }
}