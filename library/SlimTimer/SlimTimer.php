<?php

class SlimTimer_SlimTimer extends SlimTimer_Abstract
{
    public function authenticate($email, $password)
    {
        $adapter = new SlimTimer_Authenticate();
        $adapter->setApiKey($this->apiKey);
        
        $adapter->setEmail($email);
        $adapter->setPassword($password);
        
        if ($adapter->run() !== true) {
            return false;
        }
        $this->setUserId($adapter->getUserId());
        $this->setUserToken($adapter->getUserToken());
   
        return true;
    }
    
    public function taskList()
    {
        $adapter = new SlimTimer_TaskList();
        $this->setUpAdapter($adapter);

        return $adapter->run();
    }
    
    public function taskCreate($name, $tags=null, $coworker_emails=null, $reporter_emails=null, $completed_on=null)
    {
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
        
        return $adapter->run();
    }
    
    protected function setUpAdapter(SlimTimer_Abstract $adapter)
    {
        $adapter->setApiKey($this->apiKey);
        $adapter->setUserId($this->getUserId());
        $adapter->setUserToken($this->getUserToken());
    }
}