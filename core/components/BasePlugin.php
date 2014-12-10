<?php
namespace Craft\Plugins\Postmaster\Components;

use Craft\Plugins\Postmaster\Interfaces\PluginInterface;
use Craft\Plugins\Postmaster\Interfaces\SettingsInterface;

abstract class BasePlugin extends Settings implements PluginInterface, SettingsInterface {

	public function registerCpRoutes()
	{
		return array();
	}

}