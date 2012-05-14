<?php

abstract class SlimTimer_DtoAbstract
{
    public function __set($name, $value)
    {
        $property = $this->mapProperty($name);
        $this->$property = $value;
    }
    
    public function __get($name)
    {
        $property = $this->mapProperty($name);
        return $this->$property;
    }
    
    public function mapProperty($name)
    {
        $property = '_' . $name;
        if (!property_exists($this, $property)) {
            throw new Exception('Invalid property: '. $name);
        }
        
        return $property;
    }
}