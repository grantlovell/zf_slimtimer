<?php

class SlimTimer_TimeMapper
{
    public function createGroupFromXml(SimpleXMLElement $xml)
    {
        $timeEntries = array();
        foreach ($xml AS $task) {
            $timeEntries[] = $this->createFromXml($task);
        }
        return $timeEntries;
    }
    
    public function createFromXml(SimpleXMLElement $xml)
    {
        $taskMapper = new SlimTimer_TaskMapper();
        $time = new SlimTimer_Time();
        $time->id = (int) $xml->id;
        $time->start_time = date('Y-m-d H:i:s', strtotime($xml->{'start-time'}));
        $time->end_time = date('Y-m-d H:i:s', strtotime($xml->{'end-time'}));
        $time->duration_in_seconds = (int) $xml->{'duration-in-seconds'};
        $time->tags = (string) $xml->tags ? explode(',', (string) $xml->tags) : array();
        $time->comments = (string) $xml->comments;
        $time->in_progress = ((string) $xml->{'in-progress'} === "false") ? false : true;
        $time->task = $taskMapper->createFromXml($xml->task);
        $time->updated_at = date('Y-m-d H:i:s', strtotime($xml->{'updated-at'}));
        $time->created_at = date('Y-m-d H:i:s', strtotime($xml->{'created-at'}));
        return $time;
    }
}