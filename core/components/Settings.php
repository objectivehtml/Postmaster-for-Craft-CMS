<?php
namespace Craft\Plugins\Postmaster\Components;

abstract class Settings extends Base  {

	protected $settings = array();

	public function getSettings()
	{
		return $this->settings;
	}

	public function getSetting($key)
    {
        if(isset($this->settings->$key))
        {
            return $this->settings->$key;
        }

        return;
    }

	public function setSettings($settings)
	{
		$this->settings = $settings;
	}

	public function createSettingsModel($settings = false)
	{
		$class = $this->getSettingsModelClassName();

		return new $class($settings ? $settings : $this->settings);
	}
}