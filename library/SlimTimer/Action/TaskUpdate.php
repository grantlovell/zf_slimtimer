<?php

class SlimTimer_Action_TaskUpdate extends SlimTimer_Abstract
{
    protected $apiPath = "/users/__user_id__/tasks/__task_id__";
    protected $taskId;
    protected $name;
    protected $tags = array();
    protected $coworker_emails = array();
    protected $reporter_emails = array();
    protected $completed_on;
    
    public function setTaskId($value) 
    {
        if (!Zend_Validate::is($value, 'Digits')) {
            throw new InvalidArgumentException("{$value} is not a valid task id. Task id must be an integer.");
        }
        $this->taskId = (int) $value; 
    }
    
    public function setName($value) 
    {
        $this->name = (string) $value; 
    }
    
    public function setTags(array $values) 
    {
        $this->tags = implode(",", $values);
    }
    
    public function setCoworkerEmails(array $values) 
    {
        foreach ($values AS $value) {
            if (!Zend_Validate::is($value, 'EmailAddress')) {
                throw new InvalidArgumentException("{$value} is not an a valid coworker email address.");
            }
        }
        $this->coworker_emails = implode(",", $values); 
    }
    
    public function setReporterEmails(array $values) 
    {
        foreach ($values AS $value) {
            if (!Zend_Validate::is($value, 'EmailAddress')) {
                throw new InvalidArgumentException("{$value} is not an a valid reporter email address.");
            }
        }
        $this->reporter_emails = implode(",", $values);  
    }
    public function setCompletedOn($value) 
    {
        if (!Zend_Validate::is($value, 'Date')) {
            throw new InvalidArgumentException("{$value} is not a valid completed on date/time.");
        }
        $this->completed_on = date('Y-m-d H:i:s', strtotime($value)); 
    }
    
    public function run()
    {
        $this->buildApiPath();
        $this->buildRequestXml();
        
        $url = $this->apiUrl . $this->apiPath;
        $xml = $this->apiXml;
        $response = $this->makeRequest($url, $xml, 'PUT');
        $result = $this->parseXml($response);
        
        return $result;
    }
    
    protected function buildApiPath()
    {
        if (!isset($this->taskId)) {
            throw new Zend_Exception("Processing request failed. Task id must be set.");
        }
        
        $this->apiPath = "/users/{$this->userId}/tasks/{$this->taskId}";
    }
    
    protected function buildRequestXml()
    {
        if (!isset($this->name)) {
            throw new Zend_Exception("Processing request failed. Name must be set.");
        }
        
        $xml = parent::generateRequestXml();
        $taskXml = $this->buildTaskXml($xml);
        
        $this->buildNameXml($taskXml);
        
        if ($this->tags) {
            $this->buildTagsXml($taskXml);
        }
        
        if ($this->coworker_emails) {
            $this->buildCoworkersXml($taskXml);
        }
        
        if ($this->reporter_emails) {
            $this->buildReportersXml($taskXml);
        }
        
        if (isset($this->completed_on)) {
            $this->buildCompletedOnXml($taskXml);
        }
        
        $this->apiXml = $xml->asXML();
    }
    
    protected function buildTaskXml($xml)
    {
        return $xml->addChild('task');
    }
    
    protected function buildNameXml($xml)
    {
        $xml->addChild('name', $this->name);
    }
    
    protected function buildTagsXml($xml)
    {
        $xml->addChild('tags', $this->tags);
    }
    
    protected function buildCoworkersXml($xml)
    {
        $xml->addChild('coworker-emails', $this->coworker_emails);
    }
    
    protected function buildReportersXml($xml)
    {
        $xml->addChild('reporter-emails', $this->reporter_emails);
    }
    
    protected function buildCompletedOnXml($xml)
    {
        $xml->addChild('completed-on', $this->completed_on);
    }
    
    protected function parseXml(SimpleXMLElement $xml)
    {
        $taskMapper = new SlimTimer_Mapper_Task();
        return $taskMapper->createFromXml($xml);
    }
}