<?php
namespace Craft;

craft()->postmaster->registerServices(array(
    'Craft\Plugins\Postmaster\Services\CraftService',
    'Craft\Plugins\Postmaster\Services\MandrillService',
    'Craft\Plugins\Postmaster\Services\MailchimpService',
    'Craft\Plugins\Postmaster\Services\CampaignMonitorService',
    'Craft\Plugins\Postmaster\Services\TwilioService',
    'Craft\Plugins\Postmaster\Services\HttpRequestService',
   	'Craft\Plugins\Postmaster\Services\TestService',
));

craft()->postmaster->registerParcelTypes(array(
    'Craft\Plugins\Postmaster\ParcelTypes\DefaultParcelType',
    'Craft\Plugins\Postmaster\ParcelTypes\UserEmailParcelType',
    'Craft\Plugins\Postmaster\ParcelTypes\SystemEmailParcelType',
    'Craft\Plugins\Postmaster\ParcelTypes\EmailFormParcelType',
    // 'Craft\Plugins\Postmaster\ParcelTypes\TestParcelType',
));

craft()->postmaster->registerNotificationTypes(array(
    'Craft\Plugins\Postmaster\NotificationTypes\DefaultNotificationType',
    'Craft\Plugins\Postmaster\NotificationTypes\AfterUserInactivityNotificationType',
    'Craft\Plugins\Postmaster\NotificationTypes\ExpiredEntriesNotificationType'
));

craft()->postmaster->registerParcelSchedules(array(
	'Craft\Plugins\Postmaster\ParcelSchedules\DefaultParcelSchedule',
	'Craft\Plugins\Postmaster\ParcelSchedules\SendDateParcelSchedule',
));

craft()->postmaster->registerNotificationSchedules(array(
	'Craft\Plugins\Postmaster\NotificationSchedules\DefaultNotificationSchedule',
    'Craft\Plugins\Postmaster\NotificationSchedules\AnyTimeNotificationSchedule',
));