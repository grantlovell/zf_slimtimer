<?php

class SlimTimer_Action_TimeShow extends SlimTimer_Abstract
{
    protected $apiPath = "/users/__user_id__/time_entries/__time_entry_id__";
    protected $timeId;
    
    public function setTimeId($value) 
    {
        if (!Zend_Validate::is($value, 'Digits')) {
            throw new InvalidArgumentException("{$value} is not a valid time entry id. Time entry id must be an integer.");
        }
        $this->timeId = (int) $value; 
    }
    
    public function run()
    {
        $this->buildApiPath();
        $response = $this->request();
        $task = $this->parseXml($response);
        
        return $task;
    }
    
    protected function buildApiPath()
    {
        if (!isset($this->timeId)) {
            throw new Zend_Exception("Processing request failed. Time entry id must be set.");
        }
        
        $this->apiPath = "/users/{$this->userId}/time_entries/{$this->timeId}";
        $this->apiPath .= "?api_key={$this->apiKey}&access_token={$this->userToken}";
    }
    
    protected function request()
    {
        $url = $this->apiUrl . $this->apiPath;
        return $this->makeRequest($url, '', 'GET');
    }
    
    protected function parseXml(SimpleXMLElement $xml)
    {
        $taskMapper = new SlimTimer_Mapper_Time();
        return $taskMapper->createFromXml($xml);
    }
}