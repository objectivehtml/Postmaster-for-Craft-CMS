<?php
namespace Craft;

use Craft\Plugins\Postmaster\Services\CampaignMonitorService;

class Postmaster_CampaignMonitorController extends BaseController
{
	public function actionGetLists()
	{
		$settings = new Postmaster_CampaignMonitorServiceSettingsModel(array(
			'apiKey' => craft()->request->getPost('key'),
			'clientId' => craft()->request->getPost('clientId'),
			'listIds' => craft()->request->getPost('listIds')
		));

		$service = new CampaignMonitorService(array(
			'settings' => $settings
		));

		$this->renderTemplate('postmaster/services/campaignmonitor/_lists', array(
			'settings' => $settings,
			'lists' => $service->getLists()
		));
	}
}