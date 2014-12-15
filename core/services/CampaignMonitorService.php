<?php
namespace Craft\Plugins\Postmaster\Services;

use Craft\Postmaster_TransportModel;
use Craft\Postmaster_TransportResponseModel;
use Craft\Postmaster_CampaignMonitorCampaignModel;
use Craft\Postmaster_CampaignMonitorSubscriberModel;
use Craft\Plugins\Postmaster\Components\BaseService;
use Guzzle\Http\Client;

class CampaignMonitorService extends BaseService {

	public $name = 'CampaignMonitor';

	public $id = 'campaignmonitor';

	protected $requireModels = array(
		'Craft\EmailModel'
	);

	public function registerCpRoutes()
	{
		return array(
			'postmaster/campaignmonitor/lists' => array('action' => 'postmaster/campaignMonitor/getLists'),
		);
	}

	public function getLists()
	{
		$client = new Client();

		$request = $client->get($this->getApiUrl('clients/'.$this->settings->clientId.'/lists'));

		$request->setAuth($this->settings->apiKey, '');

		try
		{
			$response = $request->send();
			$response = json_decode($response->getBody());

			return $response;
		}
		catch(\Exception $e)
		{
			throw $e;
		}
	}

	public function subscribe(Array $data = array())
	{
		$model = new Postmaster_CampaignMonitorSubscriberModel($data);

		try
		{
			$model->subscribe();

			return $model;
		}
		catch(\Exception $e)
		{
			throw $e;
		}
	}

	public function createCampaign(Array $data = array())
	{
		try
		{
			$model = new Postmaster_CampaignMonitorCampaignModel($data);
			$model->create();

			return $model;
		}
		catch(\Exception $e)
		{
			throw $e;
		}
	}

	public function createAndSendCampaign(Array $data = array())
	{
		try
		{
			$model = $this->createCampaign($data);
			$model->send();

			return $model;
		}
		catch(\Exception $e)
		{
			throw $e;
		}
	}

	public function send(Postmaster_TransportModel $model)
	{
		$response = new Postmaster_TransportResponseModel(array(
			'model' => $model
		));

		if($this->settings->action == 'createAndSend')
		{

			try
	    	{
	    		$segmentIds = array();

	    		foreach($this->settings->segmentIds as $row)
	    		{
	    			$segmentIds[] = $row['id'];
	    		}

				$this->createAndSendCampaign(array(
					'name' => $this->settings->name,
		    		'apiKey' => $this->settings->apiKey,
		    		'clientId' => $this->settings->clientId,
					'listIds' => array_values(array_filter($this->settings->listIds)),
					'segmentIds' => $segmentIds,
					'subject' => $model->settings->subject,
					'fromEmail' => $model->settings->fromEmail,
					'fromName' => $model->settings->fromName,
					'replyTo' => $model->settings->replyTo,
					'htmlBody' => $model->settings->htmlBody,
					'body' => $model->settings->body,
					'confirmationEmail' => $this->settings->confirmationEmail,
				));
			}
			catch(\Exception $e)
			{
				$response->setSuccess(false);

				try
				{
					$errorResponse = json_decode($e->getResponse()->getBody());

					$response->setCode($errorResponse->Code);
					$response->addError($errorResponse->Message);
				}
				catch(\Exception $e)
				{
					$response->addError($e->getMessage());
				}
			}
		}
		else if($this->settings->action == 'subscribe')
		{
	        foreach(array_filter($this->settings->listIds) as $listId)
	        {
				try
				{
					$this->subscribe(array(
						'apiKey' => $this->settings->apiKey,
						'listId' => $listId,
						'email' => $this->settings->subscriberEmail,
						'name' => $this->settings->subscriberName,
						'customFields' => $this->settings->customFields,
						'resubscribe' => $this->settings->resubscribe
					));
				}
				catch(\Exception $e)
				{
					$response->setSuccess(false);

					try
					{
						$errorResponse = json_decode($e->getResponse()->getBody());

						$response->setCode($errorResponse->code);
						$response->addError($errorResponse->error);
					}
					catch(\Exception $e)
					{
						$response->addError($e->getMessage());
					}
				}
			}
		}

		return $response;
	}

	public function getInputHtml(Array $data = array())
	{
		return $this->craft()->templates->render('postmaster/services/campaignmonitor/settings', $data);
	}

	public function getSettingsModelClassName()
	{
		return '\Craft\Postmaster_CampaignMonitorServiceSettingsModel';
	}

	protected function getApiUrl($method)
	{
		return 'https://api.createsend.com/api/v3.1/'.$method.'.json?pretty=true';
	}

}