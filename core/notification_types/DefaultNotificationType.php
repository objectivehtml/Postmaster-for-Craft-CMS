<?php
namespace Craft\Plugins\Postmaster\NotificationTypes;

use Craft\Craft;
use Craft\PostmasterHelper;
use Craft\Postmaster_TransportModel;
use Craft\Plugins\Postmaster\Components\BaseNotificationType;

class DefaultNotificationType extends BaseNotificationType {
	
    public function getName()
    {
        return Craft::t('Email Notification');
    }

    public function getId()
    {
        return 'default';
    }

	public function onBeforeSend(Postmaster_TransportModel $model)
	{
		$this->parse(array());

		if($this->hasExtraConditionals() && !$this->areExtraConditionalsValid())
		{
			return false;
		}

		return true;
	}

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

    public function hasExtraConditionals()
    {
        return PostmasterHelper::hasExtraConditionals($this->getSetting('extraConditionals'));
    }

    public function areExtraConditionalsValid()
    {
       return PostmasterHelper::validateExtraConditionals($this->getSetting('extraConditionals'));
    }

}