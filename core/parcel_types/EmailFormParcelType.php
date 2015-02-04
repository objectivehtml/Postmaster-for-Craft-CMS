<?php
namespace Craft\Plugins\Postmaster\ParcelTypes;

use Craft\Craft;
use Craft\Event;
use Craft\Postmaster_TransportModel;
use Craft\PostmasterHelper;
use Craft\Plugins\Postmaster\Components\BaseParcelType;

class EmailFormParcelType extends BaseParcelType {
	
    public function getName()
    {
        return Craft::t('Frontend Email Form');
    }

    public function getId()
    {
        return 'emailForm';
    }

	public function init()
	{
		$parcelType = $this;

		$this->craft()->on('postmaster_forms.emailFormSend', function(Event $event) use ($parcelType)
		{
			$parcelType->parse(array_merge($event->params, array(
				'post' => $this->craft()->request->getPost()
			)));

			if($parcelType->validate())
	        {
	            $obj = new Postmaster_TransportModel(array(
	                'service' => $parcelType->getParcelModel()->service,
	                'settings' => $parcelType->settings,
	                'data' => $event->params
	            ));
				
	           	$parcelType->getParcelModel()->send($obj);
	        }
		});
	}

	public function validate()
	{
		return PostmasterHelper::validateExtraConditionals($this->settings->extraConditionals);
	}

	public function getInputHtml(Array $data = array())
	{
		return $this->craft()->templates->render('postmaster/parcel_types/email_form/fields', $data);
	}

	public function getSettingsInputHtml(Array $data = array())
	{
		return $this->craft()->templates->render('postmaster/parcel_types/email_form/settings', $data);
	}

	public function getSettingsModelClassName()
	{
		return '\Craft\Postmaster_EmailFormParcelTypeSettingsModel';
	}
}