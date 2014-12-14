<?php
namespace Craft\Plugins\Postmaster\NotificationSchedules;

use Carbon\Carbon;
use Craft\Postmaster_TransportModel;
use Craft\Plugins\Postmaster\Components\BaseNotificationSchedule;

class AnyTimeNotificationSchedule extends BaseNotificationSchedule {
	
	public $name = 'Any Time';

	public $id = 'anytime';

	protected $now;

	public function __construct($attributes = null)
	{
		parent::__construct($attributes);

		$this->now = Carbon::now(new \DateTimeZone(\Craft\craft()->getTimezone()));
	}

	public function getSettingsModelClassName()
	{
		return '\Craft\Postmaster_NotificationScheduleSettingsModel';
	}
}