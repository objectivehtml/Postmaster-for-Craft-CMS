<?php
namespace Craft\Plugins\Postmaster\ParcelSchedules;

use Carbon\Carbon;
use Craft\Postmaster_TransportModel;
use Craft\Plugins\Postmaster\Components\BaseParcelSchedule;

class DefaultParcelSchedule extends BaseParcelSchedule {
	
	public $name = 'Immediately';

	public $id = 'default';

	protected $now;

	public function __construct($attributes = null)
	{
		parent::__construct($attributes);

		$this->now = Carbon::now(new \DateTimeZone(\Craft\craft()->getTimezone()));
	}

	public function getSettingsModelClassName()
	{
		return '\Craft\Postmaster_ParcelScheduleSettingsModel';
	}
}