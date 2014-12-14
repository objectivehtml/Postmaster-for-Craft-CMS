<?php
namespace Craft\Plugins\Postmaster\Components;

use Craft\Postmaster_NotificationModel;
use Craft\Postmaster_TransportModel;
use Craft\Postmaster_TransportResponseModel;
use Craft\Plugins\Postmaster\Interfaces\NotificationTypeInterface;

abstract class BaseNotificationType extends BasePlugin implements NotificationTypeInterface {

	public $name;

	public $id;

	protected $service;

	protected $notification;

	public function onBeforeSend(Postmaster_TransportModel $model)
	{
		return true;
	}

	public function onAfterSend(Postmaster_TransportResponseModel $model)
	{
		return;
	}

    public function parse(Array $data = array())
    {
        $this->notification->settings->parse($data);
        
        $this->settings->parse($data);

        $this->notification->service->settings->parse(array_merge($data, array('settings' => $this->settings)));
    }

	public function getInputHtml(Array $data = array())
	{
		return '';
	}

	public function getSettingsInputHtml(Array $data = array())
	{
		return '';
	}

	public function setNotificationModel(Postmaster_NotificationModel $notification)
	{
		$this->notification = $notification;
	}

	public function getNotificationModel()
	{
		return $this->notification;
	}
}