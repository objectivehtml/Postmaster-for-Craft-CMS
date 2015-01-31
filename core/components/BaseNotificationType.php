<?php
namespace Craft\Plugins\Postmaster\Components;

use Craft\Postmaster_NotificationModel;
use Craft\Postmaster_TransportModel;
use Craft\Postmaster_TransportResponseModel;
use Craft\Plugins\Postmaster\Interfaces\NotificationTypeInterface;

abstract class BaseNotificationType extends BasePlugin implements NotificationTypeInterface {

	protected $service;

	protected $notification;

	public function onBeforeSend(Postmaster_TransportModel $model)
	{
		$this->notification->parse(array());

		return true;
	}

	public function onAfterSend(Postmaster_TransportResponseModel $model)
	{
		return;
	}

	public function onSendComplete(Postmaster_TransportResponseModel $model)
	{

	}

	public function onSendFailed(Postmaster_TransportResponseModel $model)
	{

	}

    public function parse(Array $data = array())
    {
    	$data = array_merge(array(
    		'lastSent' => $this->notification->lastSent()
    	), $data);

        $this->notification
        	->settings
        	->parse($data);
        
        $this->settings
        	->parse(array_merge($data, array('notification' => $this->notification)));

        $this->notification
        	->getNotificationSchedule()
        	->settings
        	->parse(array_merge($data, array('notification' => $this->notification)));

        $this->notification
        	->service
        	->settings->parse(array_merge($data, array('notification' => $this->notification)));
    }

	public function getInputHtml(Array $data = array())
	{
		return;
	}

	public function getSettingsInputHtml(Array $data = array())
	{
		return;
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