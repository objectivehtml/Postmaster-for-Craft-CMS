<?php
namespace Craft\Plugins\Postmaster\NotificationSchedules;

use Craft\Craft;
use Craft\Plugins\Postmaster\Components\BaseNotificationSchedule;

class AnyTimeNotificationSchedule extends BaseNotificationSchedule {
	
	public function __construct($attributes = null)
	{
		parent::__construct($attributes);
	}

	public function getName()
	{
		return Craft::t('Any Time');
	}

	public function getId()
	{
		return 'anytime';
	}

	public function getSettingsModelClassName()
	{
		return '\Craft\Postmaster_NotificationScheduleSettingsModel';
	}
}