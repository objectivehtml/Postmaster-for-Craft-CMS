<?php
namespace Craft;

use Craft\Plugins\Postmaster\Interfaces\ParseInterface;

class Postmaster_EmailModel extends EmailModel implements ParseInterface {

	public function parse(Array $data = array())
	{
		foreach($this->getAttributes() as $attr => $value)
		{
			if(is_string($value))
			{
	            $this->$attr = craft()->templates->renderString($value, $data);
			}
		}
		
		$oldPath = craft()->path->getTemplatesPath();
		
		craft()->path->setTemplatesPath(CRAFT_BASE_PATH.'templates');

		if(!empty($this->bodyTemplate))
		{
			$this->body = craft()->templates->render($this->bodyTemplate, $data);
		}

		if(!empty($this->htmlBodyTemplate))
		{
			$this->htmlBody = craft()->templates->render($this->htmlBodyTemplate, $data);
		}

		craft()->path->setTemplatesPath($oldPath);

		return $this;
	}

	protected function defineAttributes()
	{
		$attributes = parent::defineAttributes();

		$attributes['fromEmail'] = array(AttributeType::String, 'default' => '');
		$attributes['fromName'] = array(AttributeType::String, 'default' => '');
		$attributes['toName'] = array(AttributeType::String);
		$attributes['bodyTemplate'] = array(AttributeType::String);
		$attributes['htmlBodyTemplate'] = array(AttributeType::String);

		return $attributes;
	}

}