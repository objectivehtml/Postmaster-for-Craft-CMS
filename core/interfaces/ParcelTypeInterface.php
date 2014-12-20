<?php
namespace Craft\Plugins\Postmaster\Interfaces;

use Craft\Postmaster_ParcelModel;
use Craft\Postmaster_TransportModel;
use Craft\Postmaster_TransportResponseModel;
use Craft\Plugins\Postmaster\Components\BaseService;

interface ParcelTypeInterface {
	
	public function init();

	public function parse();

	public function onBeforeSend(Postmaster_TransportModel $model);
	
	public function onAfterSend(Postmaster_TransportResponseModel $model);

	public function onSendComplete(Postmaster_TransportResponseModel $model);

	public function onSendFailed(Postmaster_TransportResponseModel $model);

	public function getInputHtml(Array $data = array());

	public function getSettingsInputHtml(Array $data = array());

	public function setParcelModel(Postmaster_ParcelModel $parcel);

	public function getParcelModel();

}