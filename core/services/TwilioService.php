<?php
namespace Craft\Plugins\Postmaster\Services;

use Craft\Postmaster_TransportModel;
use Craft\Plugins\Postmaster\Components\BaseService;
use Craft\Plugins\Postmaster\Responses\TransportResponse;
use \Guzzle\Http\Client;

class TwilioService extends BaseService {

	public $name = 'Twilio';

	public $id = 'twilio';

	public function send(Postmaster_TransportModel $model)
	{
		$response = new TransportResponse($model);

		try
		{
			$client = new Client();

			$data = array(
				'From' => $this->settings->from,
				'To' => $this->settings->to,
				'Body' => $this->settings->message,
			);

			if(!empty($this->settings->mediaUrl))
			{
				$data['Message'] = $this->settings->mediaUrl;
			}

			$request = $client->post($this->getApiUrl('Messages'), array(), $data);

			$request->send();
		}
		catch(\Exception $e)
		{
			$errorResponse = json_decode($e->getResponse()->getBody());

			$response->addError($errorResponse->message());
			$response->setSuccess(false);
		}

		return $response;
	}

	public function getApiUrl($endpoint)
	{
		return $this->settings->getApiUrl($endpoint);
	}

	public function getInputHtml(Array $data = array())
	{
		return $this->craft()->templates->render('postmaster/services/twilio/settings', $data);
	}

	public function getSettingsModelClassName()
	{
		return '\Craft\Postmaster_TwilioServiceSettingsModel';
	}
}