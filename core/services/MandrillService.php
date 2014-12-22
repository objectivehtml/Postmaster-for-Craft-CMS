<?php
namespace Craft\Plugins\Postmaster\Services;

use Craft\Craft;
use Craft\Postmaster_TransportModel;
use Craft\Plugins\Postmaster\Components\BaseService;
use Craft\Plugins\Postmaster\Responses\TransportResponse;
use Guzzle\Http\Client;

class MandrillService extends BaseService {

	public $url = 'https://mandrillapp.com/api/1.0/messages/send.json';

	protected $requireModels = array(
		'Craft\EmailModel'
	);

    public function getName()
    {
        return Craft::t('Mandrill');
    }

    public function getId()
    {
        return 'mandrill';
    }

	public function send(Postmaster_TransportModel $model)
	{
		$client = new Client();
		$to = array();
		$headers = array();
		$toNames = explode(',', $model->settings->toName);
		
		if(!empty($model->settings->replyTo))
		{
			$headers['Reply-To'] = $model->settings->replyTo;
		}

		foreach(explode(',', $model->settings->toEmail) as $index => $email)
		{
			$name = null;

			if(isset($toNames[$index]))
			{
				$name = $toNames[$index];
			}

			$to[] = (object) array(
				'email' => trim($email),
				'name'  => trim($name),
				'type'  => 'to'
			);
		}

		if(!empty($model->settings->cc))
		{
			foreach(explode(',', $model->settings->cc) as $index => $email)
			{
				$to[] = (object) array(
					'email' => trim($email),
					'type' => 'cc'
				);
			}
		}

		if(!empty($model->settings->bcc))
		{
			foreach(explode(',', $model->settings->bcc) as $index => $email)
			{
				$to[] = (object) array(
					'email' => trim($email),
					'type' => 'bcc'
				);
			}
		}

		$postData = (object) array(
			'key'	   => $model->service->settings->apiKey,
			'message'  => (object) array(
				'text' => $model->settings->body,
				'html' => $model->settings->htmlBody,
				'subject' => $model->settings->subject,
				'to' => $to,
				'headers' => $headers,
				'from_email' => $model->settings->fromEmail,
				'from_name' => $model->settings->fromName,
				'track_opens' => (bool) $model->service->settings->trackOpens,
				'track_clicks' => (bool) $model->service->settings->trackClicks,
				'auto_text' => (bool) $model->service->settings->autoText,
				'url_strip_qs' => (bool) $model->service->settings->stripQuery,
				'preserve_recipients' => (bool) $model->service->settings->preserveRecipients,
			),
		);

		try
		{
			$request = $client->post($this->url, array(), json_encode($postData));
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

	public function getInputHtml(Array $data = array())
	{
		return $this->craft()->templates->render('postmaster/services/mandrill/settings', $data);
	}

	public function getSettingsModelClassName()
	{
		return '\Craft\Postmaster_MandrillServiceSettingsModel';
	}

}