<?php

class SlimTimer_Authenticate extends SlimTimer_Abstract
{
    protected $apiPath = '/users/token'; 
    protected $email;
    protected $password;
    
    public function setEmail($email) { $this->email = $email; }
    public function setPassword($password) { $this->password = $password; }
    
    public function run()
    {
        $url = $this->apiUrl . $this->apiPath;
        $response = $this->makeRequest($url, $this->getCallXml(), 'POST');
        $result = $this->parseResponse($response);
        
        if ($this->isRequestError($result) === true) {
            return false;
        }
        
        $this->userId = (string) $result->{'user-id'};
        $this->userToken = (string) $result->{'access-token'};
        return true;
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