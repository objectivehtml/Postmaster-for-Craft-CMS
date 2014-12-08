<?php
namespace Craft\Plugins\Postmaster\Services;

use Craft\Postmaster_TransportModel;
use Craft\Plugins\Postmaster\Components\BaseService;
use Craft\Plugins\Postmaster\Responses\TransportResponse;

class TestService extends BaseService {

	public $name = 'Test Email';

	public $id = 'testing';

	public function send(Postmaster_TransportModel $model)
	{
		return new TransportResponse($model, true);
	}

	public function getInputHtml(Array $data = array())
	{
		return $this->craft()->templates->render('postmaster/services/testing/settings', $data);
	}

	public function getSettingsModelClassName()
	{
		return '\Craft\Postmaster_TestServiceSettingsModel';
	}
}