<?php
namespace Craft\Plugins\Postmaster\Services;

use Craft\Craft;
use Craft\Postmaster_TransportModel;
use Craft\Plugins\Postmaster\Components\BaseService;
use Guzzle\Http\Client;
use Guzzle\Http\Exception\ClientErrorResponseException;

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
						$request = $client->post($this->settings->url, $this->settings->getHeaders(), $this->settings->getRequestVars());
						break;

					case 'put':
						$request = $client->put($this->settings->url, $this->settings->getHeaders(), $this->settings->getRequestVars());
						break;

					case 'delete':
						$request = $client->delete($this->settings->url, $this->settings->getHeaders());
						break;
				}

				$response = $request->send();

				$model->addData('responseString', (string) $response->getBody());
				$model->addData('responseJson', json_decode($model->getData('responseString')));

				return $this->success($model);
			}
			catch(ClientErrorResponseException $e)
			{
				$response = (string) $e->getResponse()->getBody();
				$json = json_decode($response);

				$model->addData('responseString', $response);

				if(is_object($json) && isset($json->errors))
				{
					if(!is_array($json->errors))
					{
						$json->errors = (array) $json->errors;
					}

					$model->addData('responseJson', $json);

					return $this->failed($model, 400, $json->errors);
				}
				else
				{
					$model->addData('responseJson', array($response));

					return $this->failed($model, 400, array($response));
				}

			}
			catch(\Exception $e)
			{
				$error = $e->getMessage();
				$json = !is_array($error) ? array('title' => $error) : $error;

				$model->addData('responseString', $error);
				$model->addData('responseJson', $json);

				return $this->failed($model, 400, $json);
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