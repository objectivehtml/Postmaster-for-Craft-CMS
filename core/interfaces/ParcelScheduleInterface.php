<?php
namespace Craft\Plugins\Postmaster\Interfaces;

use Craft\Postmaster_ParcelModel;

interface ParcelScheduleInterface extends ScheduleInterface {

	public function setParcelModel(Postmaster_ParcelModel $notification);

	public function getParcelModel();

}