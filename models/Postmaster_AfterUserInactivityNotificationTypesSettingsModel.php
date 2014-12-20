<?php
namespace Craft;

class Postmaster_AfterUserInactivityNotificationTypesSettingsModel extends Postmaster_EmailModel
{
	protected function defineAttributes()
    {
    	$attributes = parent::defineAttributes();
    	$attributes['types'] = array(AttributeType::Mixed, 'default' => array());
    	$attributes['groups'] = array(AttributeType::Mixed, 'default' => array());
    	$attributes['action'] = array(AttributeType::Mixed, 'default' => 'loginReminder');
    	$attributes['loginElapsedTime'] = array(AttributeType::String);
    	$attributes['passwordElapsedTime'] = array(AttributeType::String);
    	$attributes['forcePasswordReset'] = array(AttributeType::Bool, 'default' => 0);

        return $attributes;
    }
}