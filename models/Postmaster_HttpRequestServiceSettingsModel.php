<?php
namespace Craft;

class Postmaster_HttpRequestServiceSettingsModel extends Postmaster_ServiceSettingsModel
{
	public function parse(Array $data = array())
	{
		$parsedVars = array();

		foreach($this->postVars as $row => $vars)
		{
			$vars['value'] = trim(craft()->templates->renderString($vars['value'], $data));

			$parsedVars[] = $vars;
		}

		$this->postVars = $parsedVars;

		$parsedFiles = array();

		foreach($this->files as $row => $vars)
		{
			$vars['value'] = trim(craft()->templates->renderString($vars['value'], $data));

			$parsedFiles[] = $vars;
		}

		$this->files = $parsedFiles;
		
		parent::parse($data);
		
        return $this;
	}

	public function getHeaders()
	{
		$headers = array();

		foreach($this->headers as $header)
		{
			$headers[$header['name']] = $header['value'];
		}

		return $headers;
	}

	public function getRequestVars()
	{
		$vars = array();

		foreach($this->postVars as $var)
		{
			$vars[$var['name']] = trim($var['value']);
		}

		foreach($this->files as $var)
		{
			$value = trim($var['value']);

			if(!empty($value))
			{
				// $vars[$var['name']] = fopen($value, 'r');
				$vars[$var['name']] = '@' . $value;
			}
		}

		return $vars;
	}

	protected function defineAttributes()
    {
        return array(
            'url' => array(AttributeType::String),
            'action' => array(AttributeType::String, 'default' => 'post'),
            'postVars' => array(AttributeType::Mixed, 'default' => array()),
            'headers' => array(AttributeType::Mixed, 'default' => array()),
            'files' => array(AttributeType::Mixed, 'default' => array())
        );
    }
}