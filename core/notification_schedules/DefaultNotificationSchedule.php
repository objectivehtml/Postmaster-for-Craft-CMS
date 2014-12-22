<?php
namespace Craft\Plugins\Postmaster\NotificationSchedules;

use Carbon\Carbon;
use Craft\Craft;
use Craft\Postmaster_TransportModel;
use Craft\Plugins\Postmaster\Components\BaseNotificationSchedule;

class DefaultNotificationSchedule extends BaseNotificationSchedule {
	
	protected $now;

	public function __construct($attributes = null)
	{
		parent::__construct($attributes);

		$this->now = Carbon::now(new \DateTimeZone(\Craft\craft()->getTimezone()));
	}

    public function getName()
    {
        return Craft::t('Basic Reoccurring');
    }

    public function getId()
    {
        return 'default';
    }

	public function shouldSend($lastSent = false)
	{
		if(!$this->hasElapsedTimePassed($lastSent))
		{
			return false;
		}

        if(!$this->validateDayOfWeek())
        {
        	return false;
        }
        
        if(!$this->validateDay())
        {
        	return false;
        }

        if(!$this->validateMonth())
        {
        	return false;
        }

        if(!$this->validateTime())
        {
        	return false;
        }

        return true;
	}

	public function validateDayOfWeek()
	{
		if($this->settings->dayOfWeek !== '*')
        {
            if((int) $this->settings->dayOfWeek !== $this->now->dayOfWeek)
            {
                return false;
            }
        }

        return true;
	}

	public function validateDay()
	{
		if($this->settings->day !== '*')
        {
            if((int) $this->settings->day !== $this->now->day)
            {
                return false;
            }
        }

        return true;
	}

	public function validateMonth()
	{		
        if($this->settings->month !== '*')
        {
            if((int) $this->settings->month !== $this->now->month)
            {
                return false;
            }
        }

        return true;
	}

	public function validateTime()
	{
		if(!empty($this->settings->time['time']))
        {
            $date = Carbon::parse($this->settings->time['time'], craft()->getTimezone());

            if($this->now->diffInMinutes($date, false) !== 0)
            {
                return false;
            }
        }

        return true;
	}

	public function hasElapsedTimePassed($lastSent)
	{
		if($lastSent)
        {
            if(!empty($this->settings->elapsedInterval) && $this->settings->elapsedTime != '*')
            {
                switch ($this->settings->elapsedTime)
                {
                    case 'seconds':
                        $lastSent->addSeconds($this->settings->elapsedInterval);
                        break;
                    
                    case 'minutes':
                        $lastSent->addMinutes($this->settings->elapsedInterval);
                        break;
                    
                    case 'days':
                        $lastSent->addDays($this->settings->elapsedInterval);
                        break;
                    
                    case 'weeks':
                        $lastSent->addWeeks($this->settings->elapsedInterval);
                        break;
                    
                    case 'months':
                        $lastSent->addMonths($this->settings->elapsedInterval);
                        break;
                    
                    case 'years':
                        $lastSent->addYears($this->settings->elapsedInterval);
                        break;
                }

                if($lastSent->isFuture())
                {
                    return false;
                }
            }   
        }

        return true;
	}

	public function getInputHtml(Array $data = array())
	{
		return $this->craft()->templates->render('postmaster/notification_schedules/default/settings', $data);	
	}

	public function getSettingsModelClassName()
	{
		return '\Craft\Postmaster_DefaultNotificationScheduleSettingsModel';
	}
}