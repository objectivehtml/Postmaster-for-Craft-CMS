<?php
namespace Craft\Plugins\Postmaster\ParcelTypes;

use Craft\Postmaster_ParcelModel;
use Craft\Plugins\Postmaster\Components\BaseParcelType;

class TestParcelType extends BaseParcelType {
	
	public $name = 'Testing';

	public $id = 'testing';

	public function getSettingsModelClassName()
	{
		return '\Craft\Postmaster_DefaultParcelTypeSettingsModel';
	}
}