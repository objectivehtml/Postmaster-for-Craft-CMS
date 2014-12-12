<?php
namespace Craft;

class Postmaster_QueueController extends BaseController
{
	public function actionMarshal()
	{
		craft()->postmaster_queue->marshal();
	}
}