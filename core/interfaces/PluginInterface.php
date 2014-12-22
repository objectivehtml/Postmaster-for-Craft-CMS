<?php
namespace Craft\Plugins\Postmaster\Interfaces;

interface PluginInterface {
	
	public function getName();

	public function getId();

	public function init();

	public function is($id);

	public function registerCpRoutes();

	public function registerSiteRoutes();

}