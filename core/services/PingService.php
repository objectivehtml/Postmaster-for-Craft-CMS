<?php
namespace Craft\Plugins\Postmaster\Services;

use Craft\Postmaster_TransportModel;
use Craft\Plugins\Postmaster\Components\BaseService;
use Craft\Plugins\Postmaster\Responses\TransportResponse;
use \Guzzle\Http\Client;

class PingService extends BaseService {

	public $name = 'Ping Service';

	public $id = 'ping';

	public function send(Postmaster_TransportModel $model)
	{
		if(!empty($this->settings->url))
		{
			$data = array_merge(array(
				'settings' => $model->settings
			), $model->data);

			$this->settings->parse($data);

			$guzzle = new Client();
			$response = new TransportResponse($model, false);

			try
			{
				$guzzle->post($this->settings->url, array(), $this->settings->postVars);
				$response->setSuccess(true);
			}
			catch(Exception $e)
			{
				$response->setErrors(array(
					Craft::t('Guzzle client failed to connect to ' . $this->settings->url)
				));
			}

			return $response;
		}
	}

	public function getInputHtml(Array $data = array())
	{
		return $this->craft()->templates->render('postmaster/services/ping/settings', $data);
	}

	public function getSettingsModelClassName()
	{
		return '\Craft\Postmaster_PingServiceSettingsModel';
	}
}