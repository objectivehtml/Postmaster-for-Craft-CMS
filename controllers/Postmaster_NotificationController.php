<?php
namespace Craft;

class Postmaster_NotificationController extends BaseController
{
	public function actionCreateNotification()
	{
		$model = new Postmaster_NotificationModel();

        $this->renderTemplate('postmaster/_notification', array(
        	'header' => Craft::t('New Notification'),
        	'model' => $model
        ));
	}

	public function actionEditNotification()
	{
		$id = craft()->request->getSegment(3);
		
		$model = craft()->postmaster_notifications->findById($id);

        $this->renderTemplate('postmaster/_notification', array(
        	'header' => Craft::t('New Notification'),
        	'model' => $model
        ));
	}

	public function actionDeleteNotification()
	{
		$id = craft()->request->getSegment(4);
		
		craft()->postmaster_notifications->delete($id);

		$this->redirect('postmaster/notifications');
	}

	public function actionSaveNotification()
	{
		$this->requirePostRequest();

		$record = craft()->postmaster_notifications->create(array(
			'title' => craft()->request->getPost('title'),
			'enabled' => craft()->request->getPost('enabled'),
			'settings' => craft()->request->getPost('settings')
		));

		$this->redirect('postmaster/notifications');		
	}

	public function actionUpdateNotification()
	{
		$this->requirePostRequest();
	
		$id = craft()->request->getSegment(3);

		$record = craft()->postmaster_notifications->update($id, array(
			'title' => craft()->request->getPost('title'),
			'enabled' => craft()->request->getPost('enabled'),
			'settings' => craft()->request->getPost('settings')
		));

		$this->redirect('postmaster/notifications');		
	}

	public function actionMarshal()
	{
		if($id = craft()->request->getSegment(4))
		{
			$notification = craft()->postmaster_notifications->findById($id);

			if((int) $notification->enabled !== 0)
			{
				$notification->marshal();
			}
		}
		else
		{
	        foreach(craft()->postmaster_notifications->findEnabled() as $notification)
	        {
	            $notification->marshal();
	        }
	    }
	}
}