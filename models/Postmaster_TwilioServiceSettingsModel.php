<?php
namespace Craft;

class Postmaster_TwilioServiceSettingsModel extends Postmaster_ServiceSettingsModel
{
	public function getApiUrl($endpoint)
	{
		return 'https://'.$this->accountSid.':'.$this->authToken.'@api.twilio.com/2010-04-01/Accounts/' . $this->accountSid . '/' . $endpoint . '.json';
	}

	protected function defineAttributes()
    {
        return array(
        	'accountSid' => array(AttributeType::String),
        	'authToken' => array(AttributeType::String),
        	'from' => array(AttributeType::String),
        	'to' => array(AttributeType::String),
        	'message' => array(AttributeType::String),
        	'mediaUrl' => array(AttributeType::String)
        );
    }
}