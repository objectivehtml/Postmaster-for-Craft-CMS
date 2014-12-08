<?php
namespace Craft\Plugins\Postmaster\Components;

use Craft\Postmaster_ParcelModel;
use Craft\Plugins\Postmaster\Interfaces\ParcelTypeInterface;
use Craft\Plugins\Postmaster\Interfaces\SettingsInterface;

abstract class BaseParcelType extends Settings implements ParcelTypeInterface, SettingsInterface {

	public $name;

	public $id;

	protected $service;

	protected $parcel;

	public function init()
	{

	}

	public function getInputHtml(Array $data = array())
	{
		return '';
	}

	public function getSettingsInputHtml(Array $data = array())
	{
		return '';
	}

	/*
	public function send()
	{

	}

	public function setService(BaseService $service)
	{
		$this->service = $service;
	}

	public function getService()
	{
		return $this->service;
	}
	*/

	public function setParcelModel(Postmaster_ParcelModel $parcel)
	{
		$this->parcel = $parcel;
	}

	public function getParcelModel()
	{
		return $this->parcel;
	}
}