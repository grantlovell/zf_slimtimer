<?php

class SlimTimer_TaskList extends SlimTimer_Abstract
{
    protected $apiPath = '/users/___user_id___/tasks';
    
    public function run()
    {
        $this->apiPath = "/users/{$this->userId}/tasks?api_key={$this->apiKey}&access_token={$this->userToken}";
        $url = $this->apiUrl . $this->apiPath;
        $response = $this->makeRequest($url, '', 'GET');
        $result = $this->parseResponse($response);
        
        if ($this->isRequestError($result) === true) {
            return false;
        }
        
        return $this->parseXmlForTasks($result);
    }
    
    protected function parseXmlForTasks(SimpleXMLElement $tasksXml)
    {
        $tasks = array();
        foreach ($tasksXml AS $taskXml) {
            $tasks[] = $this->createTaskFromXml($taskXml);
        }
        return $tasks;
    }
    
    protected function createTaskFromXml(SimpleXMLElement $taskXml)
    {
        $task = array();
        $task['id'] = (string) $taskXml->id;
        $task['name'] = (string) $taskXml->name;
        $task['created-at'] = date('Y-m-d', strtotime($taskXml->{'created-at'}));
        $task['hours'] = (string) $taskXml->hours;
        return $task;
    }
}