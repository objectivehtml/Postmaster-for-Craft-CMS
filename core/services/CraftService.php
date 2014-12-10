<?php
namespace Craft\Plugins\Postmaster\Services;

use Craft\EmailModel;
use Craft\Postmaster_TransportModel;
use Craft\Plugins\Postmaster\Components\BaseService;
use Craft\Plugins\Postmaster\Responses\TransportResponse;

class CraftService extends BaseService {

	public $name = 'Craft';

	public $id = 'craft';

	protected $requireModels = array(
		'Craft\EmailModel'
	);

	public function send(Postmaster_TransportModel $model)
	{
		$response = $this->craft()->email->sendEmail($model->settings);

		return new TransportResponse($model, $response);
	}

	public function getInputHtml(Array $data = array())
	{
		return $this->craft()->templates->render('postmaster/services/craft/settings', $data);
	}

	public function getSettingsModelClassName()
	{
		return '\Craft\Postmaster_ServiceSettingsModel';
	}

}