<?php

class SlimTimer_PersonMapper
{
    public function createGroupFromXml(SimpleXMLElement $xml)
    {
        $people = array();
        foreach ($xml->person AS $person) {
            if ((string) $person->{'email'}) {
                $people[] = $this->createFromXml($person);
            }
        }
        return $people;
    }
    
    public function createFromXml(SimpleXMLElement $xml)
    {
        $person = new SlimTimer_Person();
        if ($id = (int) $xml->{'user-id'}) {
            $person->user_id = $id;
        }
        if ($name = (string) $xml->{'name'}) {
            $person->name = $name;
        }
        $person->email = (string) $xml->{'email'};
        return $person;
    }
}