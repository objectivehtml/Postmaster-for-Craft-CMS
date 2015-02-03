<?php
namespace Craft;

use Craft\Plugins\Postmaster\Interfaces\ParseInterface;

class Postmaster_BaseSettingsModel extends BaseModel implements ParseInterface
{
    public function parse(Array $data = array(), $recursive = true)
    {
        $this->setAttributes($this->parseArray($this->getAttributes(), $data, $recursive));

        return $this;
    }

    public function render($value, Array $data = array())
    {
        return craft()->templates->renderString($value, $data);
    }

    public function parseArray($subject, Array $data = array(), $recursive = true)
    {
        if(is_string($subject) && !empty($subject))
        {
            $subject = $this->render($subject, $data);
        }
        else if($recursive && is_array($subject) || is_object($subject))
        {
            foreach($subject as $index => $value)
            {   
                $subject[$index] = $this->parseArray($value, $data);
            }
        }
        else if(!$recursive && is_array($subject) || is_object($subject))
        {
            foreach($subject as $index => $value)
            {   
                if(is_string($value) && !empty($value))
                {
                    $subject[$index] = $this->render($value, $data);
                }
            }
        }

        return $subject;
    }

	protected function defineAttributes()
    {
    	return array();
    }
}