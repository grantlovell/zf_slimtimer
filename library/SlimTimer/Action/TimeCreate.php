<?php

class SlimTimer_Action_TimeCreate extends SlimTimer_Abstract
{
    protected $apiPath = "/users/__user_id__/time_entries";
    protected $start_time;
    protected $end_time;
    protected $duration_in_seconds;
    protected $task_id;
    protected $tags;
    protected $comments;
    protected $in_progress;
    
    public function setStartTime($value) 
    {
        $validator = new Zend_Validate_Date(array('format' => 'yyyy-MM-dd hh:mm:ss'));
        if (!$validator->isValid($value)) {
            throw new InvalidArgumentException("{$value} is not a valid <em>start_time</em> date/time (yyyy-mm-dd hh:mm:ss).");
        }
        $this->start_time = date('Y-m-d H:i:s', strtotime($value)); 
    }
    
    public function setEndTime($value) 
    {
        if (!Zend_Validate::is($value, 'Date')) {
            throw new InvalidArgumentException("{$value} is not a valid <em>end_time</em> date/time (yyyy-mm-dd hh:mm:ss).");
        }
        $this->start_time = date('Y-m-d H:i:s', strtotime($value)); 
    }
    
    public function setDurationInSeconds($value) 
    {
        if (!Zend_Validate::is($value, 'Digits')) {
            throw new InvalidArgumentException("{$value} is not a valid <em>duration_in_seconds</em>, it must be an integer.");
        }
        $this->duration_in_seconds = (int) $value; 
    }
    
    public function setTaskId($value) 
    {
        if (!Zend_Validate::is($value, 'Digits')) {
            throw new InvalidArgumentException("{$value} is not a valid <em>task_id</em>, it must be an integer.");
        }
        $this->task_id = (int) $value; 
    }
    
    public function setTags(array $values) 
    {
        $this->tags = implode(",", $values);
    }
    
    public function setComments($value) 
    {
        $this->comments = (string) $value; 
    }
    
    public function setInProgress($value) 
    {
        if ($value !== true && $value !== false) {
            throw new InvalidArgumentException("{$value} is not a valid <em>in_progress</em>, it must be true or false.");
        }
        $this->in_progress = (bool) $value; 
    }
    
    public function run()
    {
        $this->buildApiPath();
        $this->buildRequestXml();
        
        $url = $this->apiUrl . $this->apiPath;
        $xml = $this->apiXml;
        $response = $this->makeRequest($url, $xml, 'POST');
        $result = $this->parseXml($response);
        
        return $result;
    }
    
    protected function buildApiPath()
    {
        $this->apiPath = "/users/{$this->userId}/time_entries";
    }
    
    protected function buildRequestXml()
    {
        if (!isset($this->start_time)) {
            throw new Zend_Exception("Processing request failed. <em>start_time</em> must be set.");
        }
        if (!isset($this->duration_in_seconds)) {
            throw new Zend_Exception("Processing request failed. <em>duration_in_seconds</em> must be set.");
        }
        if (!isset($this->task_id)) {
            throw new Zend_Exception("Processing request failed. <em>task_id</em> must be set.");
        }
        
        $xml = parent::generateRequestXml();
        $timeXml = $this->buildTimeXml($xml);
        
        $this->buildStartTimeXml($timeXml);
        
        if (isset($this->end_time)) {
            $this->buildEndTimeXml($timeXml);
        }
        
        $this->buildDurationInSecondsXml($timeXml);
        
        $this->buildTaskIdXml($timeXml);
        
        if (isset($this->tags)) {
            $this->buildTagsXml($timeXml);
        }
        
        if (isset($this->comments)) {
            $this->buildCommentsXml($timeXml);
        }
        
        if (isset($this->in_progress)) {
            $this->buildInProgressXml($timeXml);
        }
        
        $this->apiXml = $xml->asXML();
    }
    
    protected function buildTimeXml($xml)
    {
        return $xml->addChild('time-entry');
    }
    
    protected function buildStartTimeXml($xml)
    {
        $xml->addChild('start-time', $this->start_time);
    }
    
    protected function buildEndTimeXml($xml)
    {
        $xml->addChild('end-time', $this->end_time);
    }
    
    protected function buildDurationInSecondsXml($xml)
    {
        $xml->addChild('duration-in-seconds', $this->duration_in_seconds);
    }
    
    protected function buildTaskIdXml($xml)
    {
        $xml->addChild('task-id', $this->task_id);
    }
    
    protected function buildTagsXml($xml)
    {
        $xml->addChild('tags', $this->tags);
    }
    
    protected function buildCommentsXml($xml)
    {
        $xml->addChild('comments', $this->comments);
    }
    
    protected function buildInProgressXml($xml)
    {
        $xml->addChild('in-progress', $this->in_progress);
    }
    
    protected function parseXml(SimpleXMLElement $xml)
    {
        $timeMapper = new SlimTimer_Mapper_Time();
        return $timeMapper->createFromXml($xml);
    }
}