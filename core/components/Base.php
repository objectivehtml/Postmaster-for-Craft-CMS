<?php
namespace Craft\Plugins\Postmaster\Components;

abstract class Base  {

	public function __construct($attributes = null)
	{
		$this->setAttributes($attributes);
	}

	public function craft()
	{
		return \Craft\craft();
	}

	public function getClass()
	{
		return get_class($this);
	}
	
	public function setAttribute($name, $value)
	{
		$this->$name = $value;
	}

	public function setAttributes($attributes = null)
	{
		if(isset($attributes) && is_array($attributes))
		{
			foreach($attributes as $name => $value)
			{
				$this->setAttribute($name, $value);
			}
		}
	}

	public function getAttribute()
	{
		if(isset($this->$name))
		{
			return $this->$name;
		}

		return;
	}
}