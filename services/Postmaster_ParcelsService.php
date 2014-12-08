<?php
namespace Craft;

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

    public function create(Array $parcel = array())
    {
		$record = new Postmaster_ParcelRecord();		
		$record->title = $parcel['title'];
		$record->settings = $parcel['settings'];
		$record->enabled = $parcel['enabled']; 

		$record->save();

		return $record;
    }

    public function update($id, Array $parcel = array())
    {   
		if($record = Postmaster_ParcelRecord::model()->findById($id))
		{
			$record->title = $parcel['title'];
			$record->settings = $parcel['settings'];
			$record->enabled = $parcel['enabled'];

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
