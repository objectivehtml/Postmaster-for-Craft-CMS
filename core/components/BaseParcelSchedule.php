<?php
namespace Craft\Plugins\Postmaster\Components;

use Craft\Postmaster_ParcelModel;
use Craft\Plugins\Postmaster\Interfaces\ParcelScheduleInterface;

abstract class BaseParcelSchedule extends BaseSchedule implements ParcelScheduleInterface {

	protected $parcel;

	public function setParcelModel(Postmaster_ParcelModel $parcel)
	{
		$this->parcel = $parcel;
	}

	public function getParcelModel()
	{
		return $this->parcel;
	}
}