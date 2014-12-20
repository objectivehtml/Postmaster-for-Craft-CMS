<?php
namespace Craft;

class Postmaster_ExpiredEntriesNotificationTypeSettingsModel extends Postmaster_EmailModel
{
	protected function defineAttributes()
    {
    	$attributes = parent::defineAttributes();
        $attributes['elapsedTime'] = array(AttributeType::String);
        $attributes['sendTotal'] = array(AttributeType::Number, 'default' => 1);

        return $attributes;
    }
}