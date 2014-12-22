<?php
namespace Craft\Plugins\Postmaster\Services;

use Craft\Craft;
use Craft\Postmaster_TransportModel;
use Craft\Plugins\Postmaster\Components\BaseService;
use Guzzle\Http\Client;

class HttpRequestService extends BaseService {

    public function getName()
    {
        return Craft::t('Http Request');
    }

    public function getId()
    {
        return 'http';
    }

	public function send(Postmaster_TransportModel $model)
	{
		if(!empty($this->settings->url))
		{
			$headers = $this->settings->headers;

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

				return $this->success($model);
			}
			catch(\Exception $e)
			{
				return $this->failed($model, 400, $e->getMessage());
			}
		}

		return $this->failed($model, 400, array(\Craft::t('The ping url is empty.')));
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