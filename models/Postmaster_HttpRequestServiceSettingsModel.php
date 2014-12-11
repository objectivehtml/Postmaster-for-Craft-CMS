<?php
namespace Craft;

class Postmaster_HttpRequestServiceSettingsModel extends Postmaster_ServiceSettingsModel
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
		
        return $this;
	}

	protected function defineAttributes()
    {
        return array(
            'url' => array(AttributeType::String),
            'action' => array(AttributeType::String, 'default' => 'post'),
            'postVars' => array(AttributeType::Mixed, 'default' => array()),
            'headers' => array(AttributeType::Mixed, 'default' => array())
        );
    }
}