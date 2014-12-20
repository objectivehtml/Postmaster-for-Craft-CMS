<?php
namespace Craft;

class Postmaster_DataController extends BaseController
{
	public function actionSuccessFailRate()
	{
		$this->returnJson(array(
			'sent' => craft()->postmaster_data->getSentMessagesCount(),
			'failed' => craft()->postmaster_data->getFailedMessagesCount()
		));
	}

	public function actionMonthlyBreakdown()
	{
		$json = array(
			'parcels' => array(),
			'notifications' => array()
		);

		foreach(craft()->postmaster_data->getSentParcels() as $parcel)
		{
			$json['parcels'][] = array(
				strtotime($parcel['dateCreated']) * 1000,
				(int) $parcel['count']
			);
		}

		foreach(craft()->postmaster_data->getSentNotifications() as $notification)
		{
			$json['notifications'][] = array(
				strtotime($notification['dateCreated']) * 1000,
				(int) $notification['count']
			);
		}

		$this->returnJson($json);
	}
}