<?php
namespace Craft;

use Carbon\Carbon;

class Postmaster_NotificationsService extends BaseApplicationComponent
{
	public function query(Postmaster_NotificationCriteriaModel $criteria)
	{
		$record = new Postmaster_NotificationRecord();

		$query = craft()->db->createCommand()
        	->select('*')
        	->from($record->getTableName())
        	->limit($criteria->limit)
        	->offset($criteria->offset)
        	->order($criteria->order);

        if($criteria->id)
        {
			$query->where('id = :id', array(':id' => $criteria->id));
        }

        if($criteria->uid)
        {
			$query->where('uid = :uid', array(':uid' => $criteria->uid));
        }

        if($criteria->enabled)
        {
			$query->where('enabled = :enabled', array(':enabled' => $criteria->enabled));
        }

        return $query;
	}

    public function lastSent($id)
    {
        $record = new Postmaster_NotificationSentRecord();

        $query = craft()->db->createCommand()
            ->select('*')
            ->from($record->getTableName())
            ->order('dateCreated desc');

        $query->where('notificationId = :id', array(':id' => $id));

        $result = $query->queryRow();

        return $result ? Carbon::parse($result['dateCreated'], craft()->getTimezone()) : false;
    }

	public function findEnabled()
	{
		$criteria = new Postmaster_NotificationCriteriaModel();
		$criteria->enabled = 1;

		return $this->find($criteria);
	}

	public function all()
	{
		return $this->find(new Postmaster_NotificationCriteriaModel);
	}

	public function find(Postmaster_NotificationCriteriaModel $criteria)
    {
        $query = $this->query($criteria);

        return $this->_populateModelsFromArray($query->queryAll());
    }

    public function findById($id)
    {
    	$criteria = new Postmaster_NotificationCriteriaModel(array(
    		'id' => $id
    	));

    	return $criteria->first();
    }

    public function create(Array $parcel = array())
    {
    	$parcel = new Postmaster_NotificationModel($parcel);

		$record = new Postmaster_NotificationRecord();		
		$record->title = $parcel->title;
		$record->settings = $parcel->settings;
		$record->enabled = $parcel->enabled; 

		$record->save();

		return $record;
    }

    public function update($id, Array $parcel = array())
    {   
		if($record = Postmaster_NotificationRecord::model()->findById($id))
		{
	    	$parcel = new Postmaster_NotificationModel($parcel);

			$record->title = $parcel->title;
			$record->settings = $parcel->settings;
			$record->enabled = $parcel->enabled; 

			$record->save();
		}

		return false;
    }

    public function delete($id)
    {
		if($record = Postmaster_NotificationRecord::model()->findById($id))
		{
    		return $record->delete();
    	}

    	return false;
    }

    public function createSentNotification(Postmaster_NotificationModel $notification, Postmaster_TransportResponseModel $model)
    {
        $record = new Postmaster_NotificationSentRecord();
        $record->senderId = $model->model->senderId ? (int) $model->model->senderId : null;
        $record->notificationId = $notification->id;

        $record->save();

        return $record;
    }

    protected function _populateModelsFromArray(Array $array)
    {
    	$models = array();

    	foreach($array as $row)
    	{
    		$models[] = Postmaster_NotificationModel::populateModel($row);
    	}

    	return $models;
    }

    public function onBeforeSend(Event $event)
    {
        $this->raiseEvent('onBeforeSend', $event);

        return $event;
    }

    public function onAfterSend(Event $event)
    {
        $this->raiseEvent('onAfterSend', $event);

        return $event;
    }

    public function onSendComplete(Event $event)
    {
        $this->raiseEvent('onSendComplete', $event);

        return $event;
    }

    public function onSendFailed(Event $event)
    {
        $this->raiseEvent('onSendFailed', $event);

        return $event;
    }

}
