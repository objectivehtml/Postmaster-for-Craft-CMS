<?php
namespace Craft\Plugins\Postmaster\Interfaces;

use Craft\Postmaster_ParcelModel;

interface ParcelScheduleInterface extends ScheduleInterface {

	public function setParcelModel(Postmaster_ParcelModel $parcel);

	public function getParcelModel();

}