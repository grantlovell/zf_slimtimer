<?php

class SlimTimer_TaskCreate extends SlimTimer_Abstract
{
    protected $apiPath;
    
    protected $name;
    protected $tags = array();
    protected $coworker_emails = array();
    protected $reporter_emails = array();
    protected $completed_on;
    
    public function setName($string) { $this->name = $string; }
    public function setTags(array $tags) { $this->tags = $tags; }
    public function addTag($string) { $this->tags[] = $string; }
    public function setCoworkerEmails(array $coworker_emails) { $this->coworker_emails = $coworker_emails; }
    public function addCoworkerEmail($string) { $this->coworker_emails[] = $string; }
    public function setReporterEmails(array $reporter_emails) { $this->reporter_emails = $reporter_emails; }
    public function addReporterEmail($string) { $this->reporter_emails[] = $string; }
    public function setCompleted($date) { $this->completed_on = date('Y-m-d H:i:s', strtotime($date)); }
    
    public function run()
    {
        $this->apiPath = "/users/{$this->userId}/tasks";
        
        $url = $this->apiUrl . $this->apiPath;
        $response = $this->makeRequest($url, $this->generateRequestXml(), 'POST');
        $result = $this->parseResponse($response);
        
        if ($this->isRequestError($result) === true) {
            return false;
        }
        
        return true;
    }
    
    protected function generateRequestXml()
    {
        $xml = parent::generateRequestXml();
        
        $task = $this->generateNameXml($xml);
        
        if ($this->tags) {
            $this->genenrateTagsXml($task);
        }
        
        if ($this->coworker_emails) {
            $this->genenrateCoworkerXml($task);
        }
        
        if ($this->reporter_emails) {
            $this->genenrateReporterXml($task);
        }
        
        if ($this->completed_on) {
            $this->genenrateCompletedXml($task);
        }
        
        return $xml->asXML();
    }
    
    protected function generateNameXml($xml)
    {
        $task = $xml->addChild('task');
        $task->addChild('name', $this->name);
        return $task;
    }
    
    protected function genenrateTagsXml($xml)
    {
        $tags = '';
        foreach ($this->tags AS $tag) {
            $tags .= "{$tag}, ";
        }
        $tags = substr($tags, 0, -2);
        $xml->addChild('tags', $tags);
    }
    
    protected function genenrateCoworkerXml($xml)
    {
        $coworker_emails = '';
        foreach ($this->coworker_emails AS $coworker_email) {
            $coworker_emails .= "{$coworker_email}, ";
        }
        $coworker_emails = substr($coworker_emails, 0, -2);
        $xml->addChild('coworker-emails', $coworker_emails);
    }
    
    protected function genenrateReporterXml($xml)
    {
        $reporter_emails = '';
        foreach ($this->reporter_emails AS $reporter_email) {
            $reporter_emails .= "{$reporter_email}, ";
        }
        $reporter_emails = substr($reporter_emails, 0, -2);
        $xml->addChild('reporter-emails', $reporter_emails);
    }
    
    protected function genenrateCompletedXml($xml)
    {
        $xml->addChild('completed-on', $this->completed_on);
    }
}