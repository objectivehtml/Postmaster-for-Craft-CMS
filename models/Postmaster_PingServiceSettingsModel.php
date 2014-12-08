<?php
namespace Craft;

use Craft\Plugins\Postmaster\Interfaces\ParseInterface;

class Postmaster_PingServiceSettingsModel extends BaseModel implements ParseInterface
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