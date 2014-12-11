<?php
namespace Craft;

craft()->postmaster->registerServices(array(
    'Craft\Plugins\Postmaster\Services\CraftService',
    'Craft\Plugins\Postmaster\Services\MandrillService',
    'Craft\Plugins\Postmaster\Services\MailchimpService',
    'Craft\Plugins\Postmaster\Services\TwilioService',
    'Craft\Plugins\Postmaster\Services\HttpRequestService',
   	'Craft\Plugins\Postmaster\Services\TestService',
));

craft()->postmaster->registerParcelTypes(array(
    'Craft\Plugins\Postmaster\ParcelTypes\DefaultParcelType',
    'Craft\Plugins\Postmaster\ParcelTypes\UserEmailParcelType',
    // 'Craft\Plugins\Postmaster\ParcelTypes\TestParcelType',
));