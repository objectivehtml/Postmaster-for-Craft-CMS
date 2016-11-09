<?php
namespace Craft;

class PostmasterPlugin extends BasePlugin
{
    public function getName()
    {
         return Craft::t('Postmaster');
    }

    public function getVersion()
    {
        return '0.5.2';
    }

    public function getDeveloper()
    {
        return 'Objective HTML';
    }

    public function getDeveloperUrl()
    {
        return 'https://objectivehtml.com';
    }

    public function hasCpSection()
    {
        return true;
    }

    public function addTwigExtension()
    {
        Craft::import('plugins.postmaster.twigextensions.PostmasterTwigExtension');

        return new PostmasterTwigExtension();
    }

    public function registerSiteRoutes()
    {
        $routes = array(
            'postmaster/send' => array('action' => 'postmaster/forms/send'),
            'postmaster/queue/marshal' => array('action' => 'postmaster/queue/marshal'),
            'postmaster/notifications/marshal' => array('action' => 'postmaster/notification/marshal'),
            'postmaster/notification/marshal/(?P<notificationId>\d+)' => array('action' => 'postmaster/notification/marshal'),
            'postmaster/template/html/(?P<templateId>\d+)' => array('action' => 'postmaster/template/getHtml'),
            'postmaster/template/text/(?P<templateId>\d+)' => array('action' => 'postmaster/template/getText'),
        );

        foreach(craft()->postmaster->getRegisteredServices() as $service)
        {
            $routes = array_merge($routes, $service->registerSiteRoutes());
        }

        foreach(craft()->postmaster->getRegisteredParcelTypes() as $service)
        {
            $routes = array_merge($routes, $service->registerSiteRoutes());
        }

        return $routes;
    }

    public function registerCpRoutes()
    {
        $routes = array(
            'postmaster/parcel/new' => array('action' => 'postmaster/parcel/createParcel'),
            'postmaster/parcel/(?P<parcelId>\d+)' => array('action' => 'postmaster/parcel/editParcel'),
            'postmaster/parcel/delete/(?P<parcelId>\d+)' => array('action' => 'postmaster/parcel/deleteParcel'),
            'postmaster/notification/new' => array('action' => 'postmaster/notification/createNotification'),
            'postmaster/notification/(?P<parcelId>\d+)' => array('action' => 'postmaster/notification/editNotification'),
            'postmaster/notification/delete/(?P<parcelId>\d+)' => array('action' => 'postmaster/notification/deleteNotification'),
            'postmaster/data/success-fail-rate' => array('action' => 'postmaster/data/successFailRate'),
            'postmaster/data/monthly-breakdown' => array('action' => 'postmaster/data/monthlyBreakdown'),
            // 'postmaster/queue/marshal' => array('action' => 'postmaster/queue/marshal'),
        );

        foreach(craft()->postmaster->getRegisteredServices() as $service)
        {
            $routes = array_merge($routes, $service->registerCpRoutes());
        }

        foreach(craft()->postmaster->getRegisteredParcelTypes() as $service)
        {
            $routes = array_merge($routes, $service->registerCpRoutes());
        }

        return $routes;
    }

    public function init()
    {
        require_once 'autoload.php';

        require_once 'vendor/autoload.php';

        require_once 'bootstrap.php';

        craft()->on('plugins.loadPlugins', function(Event $event)
        {
            craft()->postmaster->onInit(new Event());

            foreach(craft()->postmaster_parcels->findEnabled() as $parcel)
            {
                $parcel->init();
            }

            foreach(craft()->postmaster_notifications->findEnabled() as $notification)
            {
                $notification->init();
            }
        });
    }
}
