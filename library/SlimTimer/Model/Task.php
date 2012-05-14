<?php

class SlimTimer_Model_Task extends SlimTimer_Model_Abstract
{
    protected $_id;
    protected $_name;
    protected $_role;
    protected $_tags;
    protected $_hours;
    protected $_owners = array();
    protected $_reporters = array();
    protected $_coworkers = array();
    protected $_completed_on;
    protected $_updated_at;
    protected $_created_at;
}