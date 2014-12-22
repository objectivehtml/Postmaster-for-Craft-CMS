<?php
namespace Craft\Plugins\Postmaster\ParcelSchedules;

use Carbon\Carbon;
use Craft\Craft;
use Craft\Postmaster_TransportModel;
use Craft\Plugins\Postmaster\Components\BaseParcelSchedule;

class SendDateParcelSchedule extends BaseParcelSchedule {
	
	protected $now;

	public function __construct($attributes = null)
	{
		parent::__construct($attributes);

		$this->now = Carbon::now(new \DateTimeZone(\Craft\craft()->getTimezone()));
	}

    public function getName()
    {
        return Craft::t('By Send Date');
    }

    public function getId()
    {
        return 'senddate';
    }

    public function getSendDate()
    {
        $timezone = \Craft\craft()->getTimezone();

        $now = Carbon::now($timezone);

        if(!empty($this->settings->sendDateSpecific))
        {
            $now = Carbon::parse($this->settings->sendDateSpecific, $timezone);
        }

        if(!empty($this->settings->sendDateRelative))
        {
            $now->modify($this->settings->sendDateRelative);
        }

        return $now;
    }

    public function onBeforeSend(Postmaster_TransportModel $model)
    {
        $model->setSendDate($this->getSendDate());

        return true;
    }

	public function getInputHtml(Array $data = array())
	{
		return $this->craft()->templates->render('postmaster/parcel_schedules/send_date/settings', $data);	
	}

	public function getSettingsModelClassName()
	{
		return '\Craft\Postmaster_SendDateParcelScheduleSettingsModel';
	}
}