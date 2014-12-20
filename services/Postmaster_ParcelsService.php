<?php
namespace Craft;

use Carbon\Carbon;

class Postmaster_ParcelsService extends BaseApplicationComponent
{
	public function query(Postmaster_ParcelCriteriaModel $criteria)
	{
		$record = new Postmaster_ParcelRecord();

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

	public function findEnabled()
	{
		$criteria = new Postmaster_ParcelCriteriaModel();
		$criteria->enabled = 1;

		return $this->find($criteria);
	}

	public function all()
	{
		return $this->find(new Postmaster_ParcelCriteriaModel);
	}

	public function find(Postmaster_ParcelCriteriaModel $criteria)
    {
        $query = $this->query($criteria);

        return $this->_populateModelsFromArray($query->queryAll());
    }

    public function findById($id)
    {
    	$criteria = new Postmaster_ParcelCriteriaModel(array(
    		'id' => $id
    	));

    	return $criteria->first();
    }

    public function lastSent($id)
    {
        $record = new Postmaster_ParcelSentRecord;

        $query = craft()->db->createCommand()
            ->select('*')
            ->from($record->getTableName())
            ->order('dateCreated desc');

        $query->where('parcelId = :id', array(':id' => $id));

        $result = $query->queryRow();

        return $result ? Carbon::parse($result['dateCreated'], craft()->getTimezone()) : false;
    }

    public function create(Array $parcel = array())
    {
    	$parcel = new Postmaster_ParcelModel($parcel);

		$record = new Postmaster_ParcelRecord();		
		$record->title = $parcel->title;
		$record->settings = $parcel->settings;
		$record->enabled = $parcel->enabled; 

		$record->save();

		return $record;
    }

    public function update($id, Array $parcel = array())
    {   
		if($record = Postmaster_ParcelRecord::model()->findById($id))
		{
	    	$parcel = new Postmaster_ParcelModel($parcel);

			$record->title = $parcel->title;
			$record->settings = $parcel->settings;
			$record->enabled = $parcel->enabled; 

			$record->save();
		}

		return false;
    }

    public function delete($id)
    {
		if($record = Postmaster_ParcelRecord::model()->findById($id))
		{
    		return $record->delete();
    	}

    	return false;
    }

    public function createSentParcel(Postmaster_ParcelModel $model)
    {
        $record = new Postmaster_ParcelSentRecord();
        $record->parcelId = $model->id;
        $record->save();

        return $record;
    }

    public function onShouldSend(Event $event)
    {
        $this->raiseEvent('onShouldSend', $event);

        return $event;
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

    protected function _populateModelsFromArray(Array $array)
    {
    	$models = array();

    	foreach($array as $row)
    	{
    		$models[] = Postmaster_ParcelModel::populateModel($row);
    	}

    	return $models;
    }
}
