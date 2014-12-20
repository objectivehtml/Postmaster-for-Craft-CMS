<?php
namespace Craft\Plugins\Postmaster\Interfaces;

use Craft\Postmaster_NotificationModel;
use Craft\Postmaster_TransportModel;
use Craft\Postmaster_TransportResponseModel;

interface NotificationTypeInterface {
	
	public function init();

	public function parse();

	public function onBeforeSend(Postmaster_TransportModel $model);

	public function onAfterSend(Postmaster_TransportResponseModel $model);

	public function onSendComplete(Postmaster_TransportResponseModel $model);

	public function onSendFailed(Postmaster_TransportResponseModel $model);

	public function getInputHtml(Array $data = array());

	public function getSettingsInputHtml(Array $data = array());

	public function setNotificationModel(Postmaster_NotificationModel $notification);

	public function getNotificationModel();

}