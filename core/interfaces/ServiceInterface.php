<?php
namespace Craft\Plugins\Postmaster\Interfaces;

use Craft\BaseModel;
use Craft\Postmaster_TransportModel;

interface ServiceInterface {
	
	public function onBeforeSend();

	public function onAfterSend();

	public function send(Postmaster_TransportModel $model);

	public function getInputHtml();

	public function getRequiredModels();

	public function setRequiredModels(Array $models);

	public function validateModel(BaseModel $model);
}