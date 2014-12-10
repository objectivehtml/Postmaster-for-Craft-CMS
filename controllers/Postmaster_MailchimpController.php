<?php
namespace Craft;

use Craft\Plugins\Postmaster\Services\MailchimpService;

class Postmaster_MailchimpController extends BaseController
{
	public function actionGetLists()
	{
		$settings = new Postmaster_MailchimpServiceSettingsModel(array(
			'apiKey' => craft()->request->getPost('key'),
			'listIds' => craft()->request->getPost('listIds')
		));

		$service = new MailchimpService(array(
			'settings' => $settings
		));

		$this->renderTemplate('postmaster/services/mailchimp/_lists', array(
			'settings' => $settings,
			'lists' => $service->getLists()->data
		));
	}
}