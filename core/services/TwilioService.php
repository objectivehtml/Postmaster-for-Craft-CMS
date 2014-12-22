<?php
namespace Craft\Plugins\Postmaster\Services;

use Craft\Craft;
use Craft\Postmaster_TransportModel;
use Craft\Plugins\Postmaster\Components\BaseService;
use \Guzzle\Http\Client;

class TwilioService extends BaseService {

    public function getName()
    {
        return Craft::t('Twilio');
    }

    public function getId()
    {
        return 'twilio';
    }

	public function send(Postmaster_TransportModel $model)
	{
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

			return $this->success($model);
		}
		catch(\Exception $e)
		{
			try
			{
				$errorResponse = json_decode($e->getResponse()->getBody());

				return $this->failed($model, $errorResponse->code, array($errorResponse->message));
			}
			catch(\Exception $e)
			{
				return $this->failed($model, 400, array($e->getMessage()));
			}
		}
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