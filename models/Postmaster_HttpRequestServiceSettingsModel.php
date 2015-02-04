<?php
namespace Craft;

class Postmaster_HttpRequestServiceSettingsModel extends Postmaster_ServiceSettingsModel
{
	public function parse(Array $data = array())
	{
		$parsedHeaders = array();

		foreach($this->headers as $row => $vars)
		{
			$vars['value'] = trim(craft()->templates->renderString($vars['value'], $data));

			$parsedHeaders[] = $vars;
		}

		$this->headers = $parsedHeaders;

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

        $this->setAttributes($this->parseArray($this->getAttributes(), $data, false));
		
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