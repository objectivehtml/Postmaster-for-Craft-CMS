<?php
namespace Craft\Plugins\Postmaster\Interfaces;

use Craft\Postmaster_NotificationModel;
use Craft\Postmaster_TransportModel;
use Craft\Postmaster_TransportResponseModel;

interface NotificationScheduleInterface extends ScheduleInterface {

	public function setNotificationModel(Postmaster_NotificationModel $notification);

	public function getNotificationModel();

}