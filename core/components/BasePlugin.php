<?php
namespace Craft\Plugins\Postmaster\Components;

use Craft\Plugins\Postmaster\Interfaces\PluginInterface;
use Craft\Plugins\Postmaster\Interfaces\SettingsInterface;

abstract class BasePlugin extends Settings implements PluginInterface, SettingsInterface {

	public $__class__;

	public $settings;

	public function __construct($attributes = null)
	{
		parent::__construct($attributes);

		$class = $this->getSettingsModelClassName();

		if(!$this->settings instanceof $class)
		{
			$this->settings = $this->createSettingsModel($this->settings);
		}

		$this->__class__ = get_class($this);
	}

	public function init()
	{
		
	}

	public function registerSiteRoutes()
	{
		return array();
	}

	public function registerCpRoutes()
	{
		return array();
	}

	public function is($id)
	{
		if($this->getId() == $id || $this->getName() == $id)
		{
			return true;
		}

		return false;
	}

}