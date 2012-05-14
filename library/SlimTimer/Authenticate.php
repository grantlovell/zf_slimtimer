<?php

class SlimTimer_Authenticate extends SlimTimer_Abstract
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
        $result = $this->makeRequest($url, $this->getCallXml(), 'POST');
        
        $this->userId = (string) $result->{'user-id'};
        $this->userToken = (string) $result->{'access-token'};
    }
    
    protected function getCallXml()
    {
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