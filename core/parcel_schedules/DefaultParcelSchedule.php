<?php
namespace Craft\Plugins\Postmaster\ParcelSchedules;

use Carbon\Carbon;
use Craft\Craft;
use Craft\Postmaster_TransportModel;
use Craft\Plugins\Postmaster\Components\BaseParcelSchedule;

class DefaultParcelSchedule extends BaseParcelSchedule {
	
	public function __construct($attributes = null)
	{
		parent::__construct($attributes);
	}

    public function getName()
    {
        return Craft::t('Immediately');
    }

    public function getId()
    {
        return 'default';
    }

	public function getSettingsModelClassName()
	{
		return '\Craft\Postmaster_ParcelScheduleSettingsModel';
	}
}