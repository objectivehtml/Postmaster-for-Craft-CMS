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

	public function addPostVar($name, $value)
	{
		$vars = $this->postVars;
		$vars[] = array('name' => $name, 'value' => $value);
		$this->postVars = $vars;
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
			$vars[$var['name']] = is_string($var['value']) ? trim($var['value']) : $var['value'];
		}

		foreach($this->files as $var)
		{
			$value = trim($var['value']);

			if(!empty($value))
			{
				$values = explode("\n", $value);

				if(count($values) > 1)
				{
					foreach($values as $index => $val)
					{
						$vars[preg_replace('/\[\]$/', '', $var['name']).'['.$index.']'] = '@' . $val;
					}
				}
				else if(count($values) == 1)
				{
					$vars[$var['name']] = '@' . $values[0];
				}
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