<?php
namespace Craft\Plugins\Postmaster\Interfaces;

use Craft\Postmaster_ParcelModel;
use Craft\Plugins\Postmaster\Components\BaseService;

interface ParcelTypeInterface {
	
	public function init();

	public function getInputHtml(Array $data = array());

	public function getSettingsInputHtml(Array $data = array());

	public function setParcelModel(Postmaster_ParcelModel $parcel);

	public function getParcelModel();

}