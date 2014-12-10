<?php
namespace Craft;

craft()->postmaster->registerServices(array(
    'Craft\Plugins\Postmaster\Services\CraftService',
    'Craft\Plugins\Postmaster\Services\MandrillService',
    'Craft\Plugins\Postmaster\Services\MailchimpService',
   	'Craft\Plugins\Postmaster\Services\TestService',
    'Craft\Plugins\Postmaster\Services\PingService',
));

craft()->postmaster->registerParcelTypes(array(
    'Craft\Plugins\Postmaster\ParcelTypes\DefaultParcelType',
    // 'Craft\Plugins\Postmaster\ParcelTypes\TestParcelType',
));