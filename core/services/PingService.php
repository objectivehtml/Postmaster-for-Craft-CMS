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
		$response = new TransportResponse($model);

		if(!empty($this->settings->url))
		{
			try
			{
				$client = new Client();
				
				$request = $client->post($this->settings->url, array(), $this->settings->postVars);

				$request->send();

			}
			catch(\Exception $e)
			{
				$response->addError($e->getMessage());
				$response->setSuccess(false);
			}
		}
		else
		{
			$response->setSuccess(false);
			$response->addError(Craft::t('The ping url is empty.'));
		}

		return $response;
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