<?php

class SlimTimer_SlimTimer extends SlimTimer_Abstract
{
    public function authenticate($email, $password)
    {
        $adapter = new SlimTimer_Authenticate();
        $adapter->setApiKey($this->apiKey);
        
        $adapter->setEmail($email);
        $adapter->setPassword($password);
        
        if ($this->run($adapter) !== true) {
            return false;
        }
        
        $this->setUserId($adapter->getUserId());
        $this->setUserToken($adapter->getUserToken());
   
        return true;
    }
    
    public function taskList($show_completed = null, $role = array(), $offset = null)
    {
        $adapter = new SlimTimer_TaskList();
        $this->setUpAdapter($adapter);
        
        if (isset($show_completed)) {
            $adapter->setShowCompleted($show_completed);
        }
        if (count($role) > 0) {
            $adapter->setRole($role);
        }
        if (isset($offset)) {
            $adapter->setOffset($offset);
        }
        
        return $this->run($adapter);
    }
    
    public function taskCreate($name, $tags=null, $coworker_emails=null, $reporter_emails=null, $completed_on=null)
    {
        try {
            $adapter = new SlimTimer_TaskCreate();
            $this->setUpAdapter($adapter);
            
            $adapter->setName($name);
            if (isset($tags)) {
                $adapter->setTags($tags);
            }
            if (isset($coworker_emails)) {
                $adapter->setCoworkerEmails($coworker_emails);
            }
            if (isset($reporter_emails)) {
                $adapter->setReporterEmails($reporter_emails);
            }
            if (isset($completed_on)) {
                $adapter->setCompleted($completed_on);
            }
            
            return $this->run($adapter);
            
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
    
    public function taskShow($task_id)
    {
        $adapter = new SlimTimer_TaskShow();
        $this->setUpAdapter($adapter);
        
        // Add code

        return $this->run($adapter);
    }
    
    public function taskUpdate($task_id, $name, $tags=null, $coworker_emails=null, $reporter_emails=null, $completed_on=null)
    {
        $adapter = new SlimTimer_TaskUpdate();
        $this->setUpAdapter($adapter);
        
        // Add code

        return $this->run($adapter);
    }
    
    public function taskDelete($task_id)
    {
        $adapter = new SlimTimer_TaskDelete();
        $this->setUpAdapter($adapter);
        
        // Add code

        return $this->run($adapter);
    }
    
    public function timeList($offset=null, $range_start=null, $range_end=null)
    {
        $adapter = new SlimTimer_TimeList();
        $this->setUpAdapter($adapter);
        
        if (isset($offset)) {
            $adapter->setOffset($offset);
        }
        if (isset($range_start)) {
            $adapter->setRangeStart($range_start);
        }
        if (isset($range_end)) {
            $adapter->setRangeEnd($range_end);
        }
        
        return $this->run($adapter);
    }
    
    public function timeListByTask($task_id, $offset=null, $range_start=null, $range_end=null)
    {
        $adapter = new SlimTimer_TimeList();
        $this->setUpAdapter($adapter);
        
        $adapter->setTaskId($task_id);
        
        if (isset($offset)) {
            $adapter->setOffset($offset);
        }
        if (isset($range_start)) {
            $adapter->setRangeStart($range_start);
        }
        if (isset($range_end)) {
            $adapter->setRangeEnd($range_end);
        }
        
        return $this->run($adapter);
    }
    
    public function timeCreate($start_time, $duration_in_seconds, $task_id, $end_time, $tags=null, $comments=null, $in_progress=null)
    {
        
    }
    
    public function timeShow($time_id)
    {
        
    }
    
    public function timeUpdate($time_id, $start_time, $duration_in_seconds, $task_id, $end_time, $tags=null, $comments=null, $in_progress=null)
    {
        
    }
    
    public function timeDelete($time_id)
    {
        
    }
    
    protected function setUpAdapter(SlimTimer_Abstract $adapter)
    {
        $adapter->setApiKey($this->apiKey);
        $adapter->setUserId($this->getUserId());
        $adapter->setUserToken($this->getUserToken());
    }
    
    protected function run($adapter)
    {
        if (false === ($result = $adapter->run())) {
            $this->responseError = $adapter->getResponseError();
        }
        
        return $result;
    }
}