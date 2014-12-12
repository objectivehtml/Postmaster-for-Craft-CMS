<?php
namespace Craft\Plugins\Postmaster\Interfaces;

interface SettingsInterface {
	
	public function getSetting($key);

	public function getSettings();

	public function setSettings($settings);

	public function getSettingsModelClassName();

	public function createSettingsModel($settings = false);
}