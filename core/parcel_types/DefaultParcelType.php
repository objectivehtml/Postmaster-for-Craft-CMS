<?php
namespace Craft\Plugins\Postmaster\ParcelTypes;

use Craft\Craft;
use Craft\EntryModel;
use Craft\PostmasterHelper;
use Craft\Postmaster_TransportModel;
use Craft\Plugins\Postmaster\Components\BaseParcelType;

class DefaultParcelType extends BaseParcelType {
	
    public function getName()
    {
        return Craft::t('Entry Email');
    }

    public function getId()
    {
        return 'default';
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
                $isNewEntry = isset($event->params['isNewEntry']) ? $event->params['isNewEntry'] : false;

            	if(isset($event->params['entry']))
		        {
		            $entry = $event->params['entry'];
		        }
		        
		        if(isset($event->params['draft']))
		        {
		            $entry = $event->params['draft'];
		        }

                if($parcelType->areSectionsValid($entry))
                {        
                    $parcelType->parse(array(
                        'entry' => $entry,
                        'isNewEntry' => $isNewEntry
                    ));

    		        if($parcelType->validateEntry($entry, $event->params['isNewEntry']))
    		        {
                        $obj = new Postmaster_TransportModel(array(
                            'service' => $parcelType->getParcelModel()->service,
                            'settings' => $parcelType->settings,
                            'data' => array(
                                'entry' => $entry,
                                'isNewEntry' => $isNewEntry
                            )
                        ));

    		           	$parcelType->parcel->send($obj);
    		        }
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

        if(!$this->areExtraConditionalsValid())
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
    		$trigger = $isNewEntry ? 'new' : 'edit';

    		if(in_array($trigger, $this->settings->triggers))
    		{
    			return true;
    		}
    	}

    	return false;
    }

    public function hasExtraConditionals()
    {
        return PostmasterHelper::hasExtraConditionals($this->getSetting('extraConditionals'));
    }

    public function areExtraConditionalsValid()
    {
       return PostmasterHelper::validateExtraConditionals($this->getSetting('extraConditionals'));
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

    public function getEvents()
    {
        if(is_array($this->settings->events) && count($this->settings->events))
        {
            return $this->settings->events;
        }

        return array();
    }
}