<?php
namespace Craft;

class Postmaster_TransportResponseService extends BaseApplicationComponent
{
	public function query(Postmaster_TransportResponseCriteriaModel $criteria)
	{
		$record = new Postmaster_TransportResponseRecord();

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

        return $query;
	}

	public function findEnabled()
	{
		$criteria = new Postmaster_TransportResponseCriteriaModel();
		$criteria->enabled = 1;

		return $this->find($criteria);
	}

	public function all()
	{
		return $this->find(new Postmaster_TransportResponseCriteriaModel);
	}

	public function find(Postmaster_TransportResponseCriteriaModel $criteria)
    {
        $query = $this->query($criteria);

        return $this->_populateModelsFromArray($query->queryAll());
    }

    public function findById($id)
    {
    	$criteria = new Postmaster_TransportResponseCriteriaModel(array(
    		'id' => $id
    	));

    	return $criteria->first();
    }

    public function create(Array $response = array())
    {
		$record = new Postmaster_TransportResponseRecord($response);
		$record->save();

		return $record;
    }

    public function update($id, Array $response = array())
    {   
		if($record = Postmaster_TransportResponseRecord::model()->findById($id))
		{
			$record->setAttributes($response);
			$record->save();
		}

		return false;
    }

    public function delete($id)
    {
		if($record = Postmaster_TransportResponseRecord::model()->findById($id))
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
            $data = (array) $this->_expandModelsInObject(json_decode($row['model']));            
            $errors = json_decode($row['errors']);
            $model = new Postmaster_TransportModel($data);

    		$models[] = new Postmaster_TransportResponseModel(array(
                'model' => $model,
                'service' => $row['service'],
                'success' => (bool) $row['success'],
                'errors' => $errors, 
                'code' => $row['code'],
                'dateCreated' => $row['dateCreated']
            ));
    	}

    	return $models;
    }

    protected function _expandModelsInObject($objects)
    {
        if(is_array($objects) || is_object($objects))
        {
            foreach ($objects as $key => $val)
            {
                if(is_array($objects))
                {
                    $objects[$key] = $this->_expandModelsInObject($val);
                }

                if(is_object($objects))
                {
                    $objects->$key = $this->_expandModelsInObject($val);
                }
            }
        }

        if(is_object($objects))
        {
            if(isset($objects->__class__))
            {
                $class = $objects->__class__;

                return new $class((array) $objects);
            }
            if (isset($objects->__criteria__))
            {
                return craft()->elements->getCriteria($objects->__criteria__, (array) $objects);
            }
            if (isset($objects->__model__))
            {
                $class = $objects->__model__;
                $model = new $class();
                $model->setAttributes((array) $objects);
                return $model;
            }
        }
        else if(is_array($objects))
        {
            if(isset($objects['__class__']))
            {
                $class = $objects['__class__'];

                return new $class($objects);
            }
            if (isset($objects['__criteria__']))
            {
                return craft()->elements->getCriteria($objects['__criteria__'], $objects);
            }
            if (isset($objects['__model__']))
            {
                $class = $objects['__model__'];
                $model = new $class();
                $model->setAttributes($objects);
                return $model;
            }
        }

        return $objects;
    }
}
