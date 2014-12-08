<?php
namespace Craft\Plugins\Postmaster\ParcelTypes;

use Craft\EntryModel;
use Craft\Postmaster_TransportModel;
use Craft\Plugins\Postmaster\Components\BaseParcelType;

class DefaultParcelType extends BaseParcelType {
	
	public $name = 'Email Parcel (Default)';

	public $id = 'default';

	public function init()
	{
		foreach($this->settings->events as $event)
        {
            $parcelType = $this;
            $settings = $this->settings;
            $parcel = $this->parcel;

        	// remove the $parcel dependency by moving all the validation and sendFromEvent methods
        	// from the ParcelModel to this class
            $this->craft()->on($event, function(\Craft\Event $event) use ($settings, $parcel, $parcelType)
            {
            	if(isset($event->params['entry']))
		        {
		            $entry = $event->params['entry'];
		        }
		        
		        if(isset($event->params['draft']))
		        {
		            $entry = $event->params['draft'];
		        }

		        if($parcelType->validateEntry($entry, $event->params['isNewEntry']))
		        {
                    $data = array(
                        'entry' => $entry
                    );

                    $obj = new Postmaster_TransportModel(array(
                        'service' => $parcel->service,
                        'settings' => $settings->parse($data),
                        'data' => $data
                    ));

		           	$parcel->send($obj);
		        }
            });
        }
	}

	public function getInputHtml(Array $data = array())
	{
		return $this->craft()->templates->render('postmaster/parcel_types/default/fields', $data);	
	}

	public function getSettingsInputHtml(Array $data = array())
	{
		return $this->craft()->templates->render('postmaster/parcel_types/default/settings', $data);	
	}

	public function getSettingsModelClassName()
	{
		return '\Craft\Postmaster_DefaultParcelTypeSettingsModel';
	}

    public function validateEntry(EntryModel $entry, $isNewEntry = false)
    {
        if(!$this->areTriggersValid($isNewEntry))
        {
        	return false;
        }

        if(!$this->areSectionsValid($entry))
        {
        	return false;
        }

        if(!$this->areStatusesValid($entry))
        {
        	return false;
        }

        if(!$this->areExtraConditionalsValid($entry))
        {
        	return false;
        }

        return true;
    }

    public function hasStatuses()
    {
        if( isset($this->settings->statuses) && 
            count($this->settings->statuses))
        {
            return true;
        }

        return false;
    }

    public function hasSections()
    {
        if( isset($this->settings->sections) && 
            count($this->settings->sections))
        {
            return true;
        }

        return false;
    }

    public function hasTriggers()
    {
    	if( isset($this->settings->triggers) &&
    		count($this->settings->triggers))
    	{
    		return true;
    	}

    	return false;
    }

    public function areTriggersValid($isNewEntry)
    {
    	if($this->hasTriggers())
    	{
    		$entryTrigger = $isNewEntry ? 'new' : 'edit';

    		if(in_array($entryTrigger, $this->settings->triggers))
    		{
    			return true;
    		}
    	}

    	return false;
    }

    public function hasExtraConditionals()
    {
        $extra = trim($this->getSetting('extraConditionals'));

        if($extra && !empty($extra))
        {
            return true;
        }

        return false;
    }

    public function areExtraConditionalsValid(EntryModel $entry)
    {
       return strtolower($this->parseExtraConditionals($entry)) !== 'false' ? true : false;
    }

    public function areStatusesValid(EntryModel $entry)
    {
        if($this->hasStatuses())
        {
            if(!in_array($entry->status, $this->settings->statuses))
            {
                return false;
            }
        }

        return true;
    }

    public function areSectionsValid(EntryModel $entry)
    {
        if($this->hasSections())
        {
            if(in_array($entry->section->id, $this->settings->sections))
            {
            	return true;
            }
        }

        return false;
    }

    public function parseExtraConditionals(EntryModel $entry)
    {
        if($this->hasExtraConditionals())
        {
            $return = craft()->templates->renderString($this->settings->extraConditionals, array(
                'entry' => $entry
            ));

            return trim($return);
        }

        return;
    }

    public function getEvents()
    {
        if(is_array($this->settings->events) && count($this->settings->events))
        {
            return $this->settings->events;
        }

        return array();
    }
}