<?php

class SlimTimer_TaskList extends SlimTimer_Abstract
{
    protected $apiPath = '/users/___user_id___/tasks';
    protected $show_completed = 'yes';
    protected $role = 'owner,coworker';
    protected $offset;
    
    public function setShowCompleted($value) 
    {
        $acceptedValues = array('yes', 'no', 'only');
        if (!in_array($value, $acceptedValues)) {
            throw new InvalidArgumentException("{$value} is not a valid show_completed option. show_completed must be 'yes', 'no', or 'only'.");
        }
        $this->show_completed = $value;
    }
    
    public function setRole(array $roles) {
        if (count($roles) === 0) {
            throw new InvalidArgumentException("You must provide at least one valid role. Role must be 'owner', 'coworker', or 'reporter'.");
        }
        
        $acceptedValues = array('owner', 'coworker', 'reporter');
        foreach ($roles AS $role) {
            if (!in_array($role, $acceptedValues)) {
                throw new InvalidArgumentException("{$role} is not a valid role. Role must be 'owner', 'coworker', or 'reporter'.");
            }
        }
        $this->role = implode(",", $roles); 
    }
    
    public function setOffset($value) 
    {
        if (!Zend_Validate::is($value, 'Digits')) {
            throw new InvalidArgumentException("{$value} is not a valid offset. Offset must be an integer.");
        }
        $this->offset = (int) $value;
    }
    
    public function run()
    {
        $this->buildApiPath();
        $response = $this->request();
        $tasks = $this->parseXml($response);
        
        if ($this->isValidResult($tasks) === false) {
            throw new Zend_Exception("Processing request failed. User id and access token do not match.");
        }
        
        return $tasks;
    }
    
    protected function buildApiPath()
    {
        $this->apiPath = "/users/{$this->userId}/tasks";

        $this->apiPath .= "?api_key={$this->apiKey}&access_token={$this->userToken}";
        
        if ($this->show_completed !== 'yes') {
            $this->apiPath .= "&show_completed={$this->show_completed}";
        }
        
        if ($this->role !== 'owner,coworker') {
            $this->apiPath .= "&role={$this->role}";
        }
        
        if (isset($this->offset)) {
            $this->apiPath .= "&offset={$this->offset}";
        }
    }
    
    protected function request()
    {
        $url = $this->apiUrl . $this->apiPath;
        return $this->makeRequest($url, '', 'GET');
    }
    
    protected function parseXml(SimpleXMLElement $xml)
    {
        $taskMapper = new SlimTimer_TaskMapper();
        return $taskMapper->createGroupFromXml($xml);
    }
    
    protected function isValidResult($tasks)
    {
        if (!is_array($tasks)) {
            return false;
        }
        if ($tasks[0]->id === '1653318') {
            return false;
        }
        return true;
    }
}