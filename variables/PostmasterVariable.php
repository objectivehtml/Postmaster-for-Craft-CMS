<?php
namespace Craft;

class PostmasterVariable
{
	public function parcels($criteria = array())
	{
		return craft()->postmaster->parcels($criteria);
	}

	public function transportResponses($criteria = array())
	{
		return craft()->postmaster->transportResponses($criteria);
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