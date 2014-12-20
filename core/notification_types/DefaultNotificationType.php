<?php
namespace Craft\Plugins\Postmaster\NotificationTypes;

use Craft\Plugins\Postmaster\Components\BaseNotificationType;

class DefaultNotificationType extends BaseNotificationType {
	
	public $name = 'Email Notification';

	public $id = 'default';

	public function getInputHtml(Array $data = array())
	{
		return $this->craft()->templates->render('postmaster/notification_types/default/fields', $data);	
	}

	public function getSettingsInputHtml(Array $data = array())
	{
		return $this->craft()->templates->render('postmaster/notification_types/default/settings', $data);	
	}

	public function getSettingsModelClassName()
	{
		return '\Craft\Postmaster_DefaultNotificationTypeSettingsModel';
	}
}