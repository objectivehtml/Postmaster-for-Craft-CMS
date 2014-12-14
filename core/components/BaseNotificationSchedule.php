<?php
namespace Craft\Plugins\Postmaster\Components;

use Craft\Postmaster_NotificationModel;
use Craft\Plugins\Postmaster\Interfaces\NotificationScheduleInterface;

abstract class BaseNotificationSchedule extends BaseSchedule implements NotificationScheduleInterface {

	protected $notification;

	public function setNotificationModel(Postmaster_NotificationModel $notification)
	{
		$this->notification = $notification;
	}

	public function getNotificationModel()
	{
		return $this->notification;
	}
}