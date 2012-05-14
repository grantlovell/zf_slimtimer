<?php

class SlimTimer_Mapper_Task
{
    public function createGroupFromXml(SimpleXMLElement $xml)
    {
        $tasks = array();
        foreach ($xml AS $task) {
            $tasks[] = $this->createFromXml($task);
        }
        return $tasks;
    }
    
    public function createFromXml(SimpleXMLElement $xml)
    {
        $personMapper = new SlimTimer_Mapper_Person();
        $task = new SlimTimer_Model_Task();
        $task->id = (string) $xml->id;
        $task->name = (string) $xml->name;
        $task->role = (string) $xml->role;
        $task->tags = (string) $xml->tags ? explode(',', (string) $xml->tags) : array();
        $task->hours = (float) $xml->hours;
        $task->owners = $personMapper->createGroupFromXml($xml->owners);
        $task->reporters = $personMapper->createGroupFromXml($xml->reporters);
        $task->coworkers = $personMapper->createGroupFromXml($xml->coworkers);
        $task->completed_on = (string) $xml->{'completed-on'} ? date('Y-m-d H:i:s', strtotime($xml->{'completed-on'})) : null;
        $task->updated_at = date('Y-m-d H:i:s', strtotime($xml->{'updated-at'}));
        $task->created_at = date('Y-m-d H:i:s', strtotime($xml->{'created-at'}));
        return $task;
    }
}