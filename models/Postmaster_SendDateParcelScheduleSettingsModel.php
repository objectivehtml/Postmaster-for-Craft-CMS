<?php
namespace Craft;

class Postmaster_SendDateParcelScheduleSettingsModel extends Postmaster_BaseSettingsModel
{
	protected function defineAttributes()
    {
    	return array(
            'sendDateSpecific' => array(AttributeType::String, 'default' => null),
            'sendDateRelative' => array(AttributeType::String, 'default' => null),
        );
    }
}