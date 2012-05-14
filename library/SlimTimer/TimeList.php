<?php

class SlimTimer_TimeList extends SlimTimer_Abstract
{
    protected $apiPath = '/users/___user_id___/time_entries';
    protected $taskId;
    protected $offset;
    protected $rangeStart;
    protected $rangeEnd;
    
    public function setTaskId($value) 
    {
        if (!Zend_Validate::is($value, 'Digits')) {
            throw new InvalidArgumentException("{$value} is not a valid task id. Task id must be an integer.");
        }
        $this->taskId = (int) $value; 
    }
    
    public function setOffset($value) 
    {
        if (!Zend_Validate::is($value, 'Digits')) {
            throw new InvalidArgumentException("{$value} is not a valid offset. Offset must be an integer.");
        }
        $this->offset = (int) $value;
    }
    
    public function setRangeStart($value) 
    {
        if (!Zend_Validate::is($value, 'Date')) {
            throw new InvalidArgumentException("{$value} is not a valid range start. Range start must be a date");
        }
        $this->rangeStart = date('Y-m-d', strtotime($value)); 
    }
    
    public function setRangeEnd($value) 
    {
        if (!Zend_Validate::is($value, 'Date')) {
            throw new InvalidArgumentException("{$value} is not a valid range end. Range end must be a date");
        }
        $this->rangeEnd = date('Y-m-d', strtotime($value)); 
    }
    
    public function run()
    {
        $this->buildApiPath();
        $response = $this->request();
        
        if ($this->isValidResponse($response) === false) {
            throw new Zend_Exception("Processing request failed. User id and access token do not match.");
        }
        
        return $this->parseXml($response);
    }
    
    protected function buildApiPath()
    {
        if (!isset($this->taskId)) {
            $this->apiPath = "/users/{$this->userId}/time_entries";
        } else {
            $this->apiPath = "/users/{$this->userId}/tasks/{$this->taskId}/time_entries";
        }
        
        $this->apiPath .= "?api_key={$this->apiKey}&access_token={$this->userToken}";
        
        if (isset($this->offset)) {
            $this->apiPath .= "&offset={$this->offset}";
        }
        
        if (isset($this->rangeStart)) {
            $this->apiPath .= "&range_start={$this->rangeStart}";
        }
        
        if (isset($this->rangeEnd)) {
            $this->apiPath .= "&range_end={$this->rangeEnd}";
        }
    }
    
    protected function request()
    {
        $url = $this->apiUrl . $this->apiPath;
        return $this->makeRequest($url, '', 'GET');
    }
    
    protected function parseXml(SimpleXMLElement $xml)
    {
        $time = new SlimTimer_TimeMapper();
        return $time->createGroupFromXml($xml);
    }
    
    protected function isValidResponse(SimpleXMLElement $xml)
    {
        if (isset($xml->head)) {
            return false;
        }
        return true;
    }
}