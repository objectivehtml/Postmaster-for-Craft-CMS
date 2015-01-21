<?php
namespace Craft;

use Carbon\Carbon;

class PostmasterVariable
{
	public function send($settings, $parse = array())
	{
		$defaultSettings = array(
			'service' => 'testing',
			'serviceSettings' => array(),
			'settings' => array(),
			'settingsModel' => 'Craft\Postmaster_EmailModel',
			'data' => array(),
			'senderId' => null,
			'sendDate' => false,
			'queueId' => false,
		);

		$settings = array_merge($defaultSettings, $settings);

		if($settings['sendDate'])
		{
			$settings['sendDate'] = Carbon::parse($settings['sendDate'], craft()->getTimezone());
		}

		$settingsModel = new $settings['settingsModel']($settings['settings']);
		$settingsModel->parse($parse);

		if($service = craft()->postmaster->getRegisteredService($settings['service']))
		{
			$service = get_class($service);
			$service = new $service($settings['serviceSettings']);
		}
		else
		{
			$service = $settings['service'];
			$service = new $service($settings['serviceSettings']);
		}

		$service->settings->parse($parse);

		$model = new Postmaster_TransportModel(array(
			'settings' => $settingsModel,
			'service' => $service,
			'data' => $settings['data'],
			'senderId' => $settings['senderId'],
			'sendDate' => $settings['sendDate'],
			'queueId' => $settings['queueId']
		));

		return craft()->postmaster->send($model);
	}

	public function parcels($criteria = array())
	{
		return craft()->postmaster->parcels($criteria);
	}

	public function notifications($criteria = array())
	{
		return craft()->postmaster->notifications($criteria);
	}

	public function transportResponses($criteria = array())
	{
		return craft()->postmaster->transportResponses($criteria);
	}

	public function parcelTypes()
	{
		return craft()->postmaster->getRegisteredParcelTypes();
	}

	public function notificationTypes()
	{
		return craft()->postmaster->getRegisteredNotificationTypes();
	}

	public function notificationSchedules()
	{
		return craft()->postmaster->getRegisteredNotificationSchedules();
	}

	public function parcelSchedules()
	{
		return craft()->postmaster->getRegisteredParcelSchedules();
	}

	public function services()
	{
		return craft()->postmaster->getRegisteredServices();
	}

	public function email($attributes = null)
	{
		return new Postmaster_EmailModel($attributes);
	}

}