<?php
namespace Craft\Plugins\Postmaster\ParcelTypes;

use Craft\Craft;
use Craft\UserModel;
use Craft\Postmaster_TransportModel;

class UserEmailParcelType extends DefaultParcelType {
	
    public function getName()
    {
        return Craft::t('User Email');
    }

    public function getId()
    {
        return 'userEmail';
    }

	public function init()
	{
		foreach($this->settings->events as $event)
        {
            $parcelType = $this;

        	// remove the $parcel dependency by moving all the validation and sendFromEvent methods
        	// from the ParcelModel to this class
            $this->craft()->on($event, function(\Craft\Event $event) use ($parcelType)
            {
            	$user = $event->params['user'];

            	$isNewUser = isset($event->params['isNewUser']) ? $event->params['isNewUser'] : false;

                $parcelType->getParcelModel()->settings->parse(array_merge($event->params, array(
                    'user' => $user,
                    'isNewUser' => $isNewUser
                )));

                $parcelType->settings->parse(array_merge($event->params, array(
                    'user' => $user,
                    'isNewUser' => $isNewUser
                )));

                $parcelType->getParcelModel()->service->settings->parse(array_merge($event->params, array(
                    'user' => $user,
                    'settings' => $parcelType->settings,
                    'isNewUser' => $isNewUser
                )));

		        if($parcelType->validateUser($user, $isNewUser))
		        {
                    $obj = new Postmaster_TransportModel(array(
                        'service' => $parcelType->getParcelModel()->service,
                        'settings' => $parcelType->settings,
                        'data' => array_merge($event->params, array(
                            'user' => $user,
                    		'isNewUser' => $isNewUser
                        ))
                    ));

		           	$parcelType->getParcelModel()->send($obj);
		        }
            });
        }
	}
	
	public function areTriggersValid($isNewUser)
    {
    	if($this->hasTriggers())
    	{
    		$trigger = $isNewUser ? 'new' : 'existing';

    		if(in_array($trigger, $this->settings->triggers))
    		{
    			return true;
    		}
    	}

    	return false;
    }

	public function validateUser(UserModel $user, $isNewUser = false)
    {
        if(!$this->areTriggersValid($isNewUser))
        {
        	return false;
        }

        if(!$this->areExtraConditionalsValid())
        {
        	return false;
        }

        return true;
    }

	public function getInputHtml(Array $data = array())
	{
		return $this->craft()->templates->render('postmaster/parcel_types/user_email/fields', $data);
	}

	public function getSettingsInputHtml(Array $data = array())
	{
		return $this->craft()->templates->render('postmaster/parcel_types/user_email/settings', $data);
	}

	public function getSettingsModelClassName()
	{
		return '\Craft\Postmaster_UserEmailParcelTypeSettingsModel';
	}
}