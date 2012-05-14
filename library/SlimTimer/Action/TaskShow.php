<?php

class SlimTimer_Action_TaskShow extends SlimTimer_Abstract
{
    protected $apiPath = "/users/__user_id__/tasks/__task_id__";
    protected $taskId;
    
    public function setTaskId($value) 
    {
        if (!Zend_Validate::is($value, 'Digits')) {
            throw new InvalidArgumentException("{$value} is not a valid task id. Task id must be an integer.");
        }
        $this->taskId = (int) $value; 
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
        if (!isset($this->taskId)) {
            throw new Zend_Exception("Processing request failed. Task id must be set.");
        }
        
        $this->apiPath = "/users/{$this->userId}/tasks/{$this->taskId}";
        $this->apiPath .= "?api_key={$this->apiKey}&access_token={$this->userToken}";
    }
    
    protected function request()
    {
        $url = $this->apiUrl . $this->apiPath;
        return $this->makeRequest($url, '', 'GET');
    }
    
    protected function parseXml(SimpleXMLElement $xml)
    {
        $taskMapper = new SlimTimer_Mapper_Task();
        return $taskMapper->createFromXml($xml);
    }
}