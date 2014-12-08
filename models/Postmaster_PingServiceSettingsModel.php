<?php
namespace Craft;

class Postmaster_PingServiceSettingsModel extends BaseModel
{
	public function parse(Array $data = array())
	{
		$parsedVars = array();

		foreach($this->postVars as $row => $vars)
		{
			$vars['value'] = craft()->templates->renderString($vars['value'], $data);

			$parsedVars[] = $vars;
		}

		$this->postVars = $parsedVars;
	}

	protected function defineAttributes()
    {
        return array(
            'url' => array(AttributeType::String),
            'postVars' => array(AttributeType::Mixed, 'default' => array())
        );
    }
}