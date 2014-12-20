<?php
namespace Craft\Plugins\Postmaster\Interfaces;

use Craft\Postmaster_TransportModel;
use Craft\Postmaster_TransportResponseModel;

interface ScheduleInterface {
	
	public function init();

	public function onBeforeSend(Postmaster_TransportModel $model);
	
	public function onAfterSend(Postmaster_TransportResponseModel $model);

	public function onSendComplete(Postmaster_TransportResponseModel $model);

	public function onSendFailed(Postmaster_TransportResponseModel $model);
	
	public function shouldSend($lastSent = false);

	public function getInputHtml(Array $data = array());

}