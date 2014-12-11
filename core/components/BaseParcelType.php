<?php
namespace Craft\Plugins\Postmaster\Components;

use Craft\Postmaster_ParcelModel;
use Craft\Plugins\Postmaster\Interfaces\ParcelTypeInterface;

abstract class BaseParcelType extends BasePlugin implements ParcelTypeInterface {

	public $name;

	public $id;

	protected $service;

	protected $parcel;

	public function getInputHtml(Array $data = array())
	{
		return '';
	}

	public function getSettingsInputHtml(Array $data = array())
	{
		return '';
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