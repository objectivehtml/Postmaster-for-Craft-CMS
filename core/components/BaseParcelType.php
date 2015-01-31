<?php
namespace Craft\Plugins\Postmaster\Components;

use Craft\Postmaster_ParcelModel;
use Craft\Postmaster_TransportModel;
use Craft\Postmaster_TransportResponseModel;
use Craft\Plugins\Postmaster\Interfaces\ParcelTypeInterface;

abstract class BaseParcelType extends BasePlugin implements ParcelTypeInterface {

	protected $service;

	protected $parcel;

	public function parse(Array $data = array())
	{
    	$data = array_merge(array(
    		'lastSent' => $this->parcel->lastSent()
    	), $data);

        $this->parcel
        	->settings
        	->parse($data, false);

        $this->settings
        	->parse(array_merge($data, array('parcel' => $this->parcel)));

        $this->parcel
        	->getParcelSchedule()
        	->settings
        	->parse(array_merge($data, array('parcel' => $this->parcel)));

        $this->parcel
        	->service
        	->settings
        	->parse(array_merge($data, array('parcel' => $this->parcel)));

	}

	public function getInputHtml(Array $data = array())
	{
		return;
	}

	public function getSettingsInputHtml(Array $data = array())
	{
		return;
	}

	public function onBeforeSend(Postmaster_TransportModel $model)
	{
		return true;
	}
	
	public function onAfterSend(Postmaster_TransportResponseModel $model)
	{
		return;
	}

	public function onSendComplete(Postmaster_TransportResponseModel $model)
	{

	}

	public function onSendFailed(Postmaster_TransportResponseModel $model)
	{
		
	}

	public function setParcelModel(Postmaster_ParcelModel $parcel)
	{
		$this->parcel = $parcel;
	}

	public function getParcelModel()
	{
		return $this->parcel;
	}
}