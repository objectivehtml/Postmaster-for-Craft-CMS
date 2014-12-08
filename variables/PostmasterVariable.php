<?php
namespace Craft;

class PostmasterVariable
{
	public function parcels($criteria = array())
	{
		return craft()->postmaster->parcels($criteria);
	}

	public function parcelTypes()
	{
		return craft()->postmaster->getRegisteredParcelTypes();
	}

	public function services()
	{
		return craft()->postmaster->getRegisteredServices();
	}
}