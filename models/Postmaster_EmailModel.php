<?php
namespace Craft;

use Craft\Plugins\Postmaster\Interfaces\ParseInterface;

class Postmaster_EmailModel extends EmailModel implements ParseInterface {

	public function __construct($attributes = null)
	{
		parent::__construct($attributes);
	}

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
		
		craft()->path->setTemplatesPath(CRAFT_TEMPLATES_PATH);

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

		$attributes['fromEmail'] = array(AttributeType::String);
		$attributes['fromName'] = array(AttributeType::String);
		$attributes['cc'] = array(AttributeType::String);
		$attributes['bcc'] = array(AttributeType::String);
		$attributes['toName'] = array(AttributeType::String);
		$attributes['bodyTemplate'] = array(AttributeType::String);
		$attributes['htmlBodyTemplate'] = array(AttributeType::String);

		return $attributes;
	}

}