<?php
namespace Craft;

use Carbon\Carbon;

class Postmaster_SentMessagesWidget extends BaseWidget
{
	public function getName()
    {
        return Craft::t('Sent Messages');
    }

    public function getBodyHtml()
    {
    	$now = Carbon::now(craft()->getTimezone());

        return craft()->templates->render('postmaster/_widgets/sent_messages/body', array(
        	'totalSent' => craft()->postmaster_data->getSentMessagesCount(),
        	'thisWeek' => craft()->postmaster_data->getSentMessagesCount($now->copy()->startOfWeek(), $now->copy()->endOfWeek())
        ));
    }

    public function getSettingsHtml()
    {
        return craft()->templates->render('postmaster/_widgets/sent_messages/settings');
    }
}