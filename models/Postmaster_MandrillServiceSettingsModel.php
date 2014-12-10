<?php
namespace Craft;

class Postmaster_MandrillServiceSettingsModel extends Postmaster_ServiceSettingsModel
{
	protected function defineAttributes()
    {
        return array(
        	'apiKey' => array(AttributeType::String),
        	'trackOpens' => array(AttributeType::Bool, 'default' => true),
        	'trackClicks' => array(AttributeType::Bool, 'default' => true),
        	'autoText' => array(AttributeType::Bool, 'default' => false),
        	'stripQuery' => array(AttributeType::Bool, 'default' => false),
        	'preserveRecipients' => array(AttributeType::Bool, 'default' => false),
        );
    }
}