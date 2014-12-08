<?php
namespace Craft\Plugins\Postmaster\Components;

use Craft\BaseModel;
use Craft\Plugins\Postmaster\Interfaces\ServiceInterface;
use Craft\Plugins\Postmaster\Interfaces\SettingsInterface;

abstract class BaseService extends Settings implements ServiceInterface, SettingsInterface {

	public $name;

	public $id;

	public $settings;

	protected $requireModels = array();

	public function getInputHtml(Array $data = array())
	{
		return '';
	}

	public function onBeforeSend()
	{
		// Do something before the email sends
	}

	public function onAfterSend()
	{
		// Do something after the email sends
	}

	public function getRequiredModels()
	{
		return $this->requireModels;
	}

	public function setRequiredModels(Array $models)
	{
		$this->requireModels = $models;
	}

	public function validateModel(BaseModel $model)
	{
		$required = $this->getRequiredModels();

		if(!count($required))
		{
			return true;
		}

		if(in_array(get_class($model), $required))
		{
			return true;
		}

		foreach($this->requireModels as $class)
		{
			if(is_subclass_of($model, $class))
			{
				return true;
			}
		}

		return false;
	}
	
}