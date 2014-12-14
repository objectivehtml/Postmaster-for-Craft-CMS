<?php
namespace Craft;

class PostmasterVariable
{
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
}