<?php
namespace Craft;

class Postmaster_DefaultNotificationScheduleSettingsModel extends Postmaster_BaseSettingsModel
{
	protected function defineAttributes()
	{
		return array(
            'dayOfWeek' => array(AttributeType::String, 'default' => '*'),
            'day' => array(AttributeType::String, 'default' => '*'),
            'month' => array(AttributeType::String, 'default' => '*'),
            'time' => array(AttributeType::String, 'default' => array('time' => '')),
            'elapsedTime' => array(AttributeType::String, 'default' => '*'),
            'elapsedInterval' => array(AttributeType::String, 'default' => ''),
		);
	}
}