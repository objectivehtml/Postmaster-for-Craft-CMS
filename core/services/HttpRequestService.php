<?php
namespace Craft\Plugins\Postmaster\Services;

use Craft\Postmaster_TransportModel;
use Craft\Plugins\Postmaster\Components\BaseService;
use Craft\Plugins\Postmaster\Responses\TransportResponse;
use \Guzzle\Http\Client;

class HttpRequestService extends BaseService {

	public $name = 'Http Request';

	public $id = 'http';

	public function send(Postmaster_TransportModel $model)
	{
		$response = new TransportResponse($model);
		$headers = $this->settings->headers;

		if(!empty($this->settings->url))
		{
			try
			{
				$client = new Client();

				switch($this->settings->action)
				{
					case 'get':
						$request = $client->get($this->settings->url, $headers);
						$query = $request->getQuery();

						foreach($this->settings->postVars as $var)
						{
							$query->add($var['name'], $var['value']);
						}

						break;

					case 'post':
						$request = $client->post($this->settings->url, $headers, $this->settings->postVars);
						break;

					case 'put':
						$request = $client->put($this->settings->url, $headers, $this->settings->postVars);
						break;

					case 'delete':
						$request = $client->get($this->settings->url, $headers, $this->settings->postVars);
						break;
				}

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
			$response->addError(\Craft::t('The ping url is empty.'));
		}

		return $response;
	}

	public function getInputHtml(Array $data = array())
	{
		return $this->craft()->templates->render('postmaster/services/http/settings', $data);
	}

	public function getSettingsModelClassName()
	{
		return '\Craft\Postmaster_HttpRequestServiceSettingsModel';
	}
}