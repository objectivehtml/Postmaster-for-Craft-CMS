<?php
namespace Craft\Plugins\Postmaster\ParcelTypes;

use Craft\Craft;
use Craft\Event;
use Craft\Postmaster_ParcelModel;
use Craft\Postmaster_TransportModel;
use Craft\Plugins\Postmaster\Components\BaseParcelType;

class SystemEmailParcelType extends BaseParcelType {
	
    public function getName()
    {
        return Craft::t('System Email');
    }

    public function getId()
    {
        return 'systemEmail';
    }

	public function init()
	{
		$parcelType = $this;

		$this->craft()->on('email.beforeSendEmail', function(Event $event) use ($parcelType)
		{
			if($parcelType->parcel->service->is('craft'))
			{
				throw new \Craft\Exception(\Craft\Craft::t('You cannot override Craft system emails and use the Craft Postmaster service. To fix this error, edit your system email parcel and change the service to something other than Craft. If you cannot edit parcels in Postmaster or have no idea what a parcel or Postmaster is, it\'s probably best to contact your site administrator'));
			}

			$event->performAction = false;

			$parcelType->parse($event->params);

            $obj = new Postmaster_TransportModel(array(
                'service' => $parcelType->parcel->service,
                'settings' => $parcelType->settings,
                'data' => $event->params
            ));

           	$parcelType->parcel->send($obj);
		});
	}

	public function getInputHtml(Array $data = array())
	{
		return $this->craft()->templates->render('postmaster/parcel_types/system_email/fields', $data);	
	}

	public function getSettingsInputHtml(Array $data = array())
	{
		return $this->craft()->templates->render('postmaster/parcel_types/system_email/settings', $data);	
	}

	public function getSettingsModelClassName()
	{
		return '\Craft\Postmaster_EmailModel';
	}
}