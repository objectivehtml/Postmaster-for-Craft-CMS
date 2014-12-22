<?php
namespace Craft;

class Postmaster_NotificationSettingsModel extends Postmaster_BasePluginSettingsModel
{
    public function __construct($attributes = null)
    {
        parent::__construct($attributes);
    }

    public function setAttributes($attributes = null)
    {
        parent::setAttributes($attributes);

        $this->_sanitizeSettings();
    }

    public function getNotificationTypeSettingsById($id)
    {
        $settings = array();

        if(isset($this->notificationTypeSettings[$id]))
        {
            $settings = $this->notificationTypeSettings[$id];
        }

        return $settings;
    }

    public function setNotificationTypeSettings($id, $settings = array())
    {        
        if(isset($this->notificationTypeSettings[$id]))
        {
            $newSettings = $this->notificationTypeSettings;
            $newSettings[$id] = $settings;

            $this->notificationTypeSettings = $newSettings;
        }
    }

    public function getNotificationScheduleSettingsById($id)
    {
        $settings = array();

        if(isset($this->notificationScheduleSettings[$id]))
        {
            $settings = $this->notificationScheduleSettings[$id];
        }

        return $settings;
    }

    public function setNotificationScheduleSettings($id, $settings = array())
    {        
        if(isset($this->notificationScheduleSettings[$id]))
        {
            $newSettings = $this->notificationScheduleSettings;
            $newSettings[$id] = $settings;

            $this->notificationScheduleSettings = $newSettings;
        }
    }

    protected function _sanitizeSettings()
    {
        parent::_sanitizeSettings();

        $notificationTypeSettings = $this->notificationTypeSettings;

        foreach(craft()->postmaster->getRegisteredNotificationTypes() as $notificationType)
        {
            if(!isset($notificationTypeSettings[$notificationType->getId()]))
            {
                $notificationTypeSettings[$notificationType->getId()] = array();
            }
        }

        $this->notificationTypeSettings = $notificationTypeSettings;

        foreach($this->notificationTypeSettings as $id => $notificationTypeSettings)
        {
            if($class = craft()->postmaster->getRegisteredNotificationType($id))
            {
                $settings = $class->createSettingsModel($notificationTypeSettings);

                $this->setNotificationTypeSettings($id, $settings);
            }
        }

        $notificationScheduleSettings = $this->notificationScheduleSettings;

        foreach(craft()->postmaster->getRegisteredNotificationSchedules() as $notificationSchedule)
        {
            if(!isset($notificationScheduleSettings[$notificationSchedule->getId()]))
            {
                $notificationScheduleSettings[$notificationSchedule->getId()] = array();
            }
        }

        $this->notificationScheduleSettings = $notificationScheduleSettings;

        foreach($this->notificationScheduleSettings as $id => $notificationScheduleSettings)
        {
            if($class = craft()->postmaster->getRegisteredNotificationSchedule($id))
            {
                $settings = $class->createSettingsModel($notificationScheduleSettings);

                $this->setNotificationScheduleSettings($id, $settings);
            }
        }
    }

    protected function defineAttributes()
    {
        return array(
            'notificationType' => array(AttributeType::String, 'default' => 'Craft\Plugins\Postmaster\NotificationTypes\DefaultNotificationType'),
            'notificationTypeSettings' => array(AttributeType::Mixed, 'default' => array()),
            'notificationSchedule' => array(AttributeType::String, 'default' => 'Craft\Plugins\Postmaster\NotificationSchedules\DefaultNotificationSchedule'),
            'notificationScheduleSettings' => array(AttributeType::Mixed, 'default' => array()),
            'service' => array(AttributeType::String, 'default' => 'Craft\Plugins\Postmaster\Services\CraftService'),
            'serviceSettings' => array(AttributeType::Mixed, 'default' => array()),
        );
    }
}