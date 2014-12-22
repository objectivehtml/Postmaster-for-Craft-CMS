<?php
namespace Craft\Plugins\Postmaster\ParcelTypes;

use Craft\Craft;
use Craft\Postmaster_ParcelModel;
use Craft\Plugins\Postmaster\Components\BaseParcelType;

class TestParcelType extends BaseParcelType {
	
    public function getName()
    {
        return Craft::t('Testing');
    }

    public function getId()
    {
        return 'testing';
    }

	public function getSettingsModelClassName()
	{
		return '\Craft\Postmaster_DefaultParcelTypeSettingsModel';
	}
}