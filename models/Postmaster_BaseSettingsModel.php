<?php
namespace Craft;

use Craft\Plugins\Postmaster\Interfaces\ParseInterface;

class Postmaster_BaseSettingsModel extends BaseModel implements ParseInterface
{
    public function parse(Array $data = array())
    {
        $this->setAttributes($this->parseArray($this->getAttributes(), $data));

        return $this;
    }

    public function render($value, Array $data = array())
    {
        return craft()->templates->renderString($value, $data);
    }

    public function parseArray($subject, Array $data = array())
    {
        if(is_string($subject) && !empty($subject))
        {
            $subject = $this->render($subject, $data);
        }
        else if(is_array($subject) || is_object($subject))
        {
            foreach($subject as $index => $value)
            {   
                $subject[$index] = $this->parseArray($value, $data);
            }
        }

        return $subject;
    }

	protected function defineAttributes()
    {
    	return array();
    }
}