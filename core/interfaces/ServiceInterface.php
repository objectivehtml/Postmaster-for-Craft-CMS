<?php
namespace Craft\Plugins\Postmaster\Interfaces;

use Craft\BaseModel;
use Craft\Postmaster_TransportModel;
use Craft\Postmaster_TransportResponseModel;

interface ServiceInterface {
	
	public function onBeforeSend(Postmaster_TransportModel $model);

	public function onAfterSend(Postmaster_TransportResponseModel $model);

	public function send(Postmaster_TransportModel $model);

	public function getInputHtml();

	public function getRequiredModels();

	public function setRequiredModels(Array $models);

	public function validateModel(BaseModel $model);

	public function success(Postmaster_TransportModel $model, $code = 200);

	public function failed(Postmaster_TransportModel $model, $code = 400, Array $errors = array());
}