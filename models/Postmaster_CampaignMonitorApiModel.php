<?php
namespace Craft;

class Postmaster_CampaignMonitorApiModel extends BaseModel
{
	protected function getApiUrl($method)
	{
		return 'https://api.createsend.com/api/v3.1/'.$method.'.json?pretty=true';
	}

	protected function defineAttributes()
    {
        return array();
    }
}