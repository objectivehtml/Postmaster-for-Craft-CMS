<?php
namespace Craft\Plugins\Postmaster\Components;

use Craft\Postmaster_NotificationModel;
use Craft\Postmaster_TransportModel;
use Craft\Postmaster_TransportResponseModel;
use Craft\Plugins\Postmaster\Interfaces\ScheduleInterface;

abstract class BaseSchedule extends BasePlugin implements ScheduleInterface {

	public $name;

	public $id;

	public function onBeforeSend(Postmaster_TransportModel $model)
	{
		return true;
	}

	public function onAfterSend(Postmaster_TransportResponseModel $model)
	{
		return;
	}

    public function shouldSend($lastSent = false)
    {
        return true;
    }

	public function getInputHtml(Array $data = array())
	{
		return '';
	}
}