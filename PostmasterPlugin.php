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
        return '0.3.0';
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
    
    public function registerCpRoutes()
    {
        $routes = array(
            'postmaster/parcel/new' => array('action' => 'postmaster/parcel/createParcel'),
            'postmaster/parcel/(?P<parcelId>\d+)' => array('action' => 'postmaster/parcel/editParcel'),
            'postmaster/parcel/delete/(?P<parcelId>\d+)' => array('action' => 'postmaster/parcel/deleteParcel'),
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

        require_once 'bootstrap.php';

        craft()->on('plugins.loadPlugins', function(Event $event)
        {
            craft()->postmaster->onInit(new Event());

            foreach(craft()->postmaster_parcels->findEnabled() as $parcel)
            {
                $parcel->init();
            }

        });

    }
}