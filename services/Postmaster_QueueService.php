<?php
namespace Craft;

class Postmaster_QueueService extends BaseApplicationComponent
{
	public function query()
    {
        $record = new Postmaster_QueueRecord();

        return craft()->db->createCommand()
            ->select('*')
            ->from($record->getTableName());
    }

    public function remove($id)
    {
        return (new Postmaster_QueueRecord())->deleteByPk($id);
    }
    
    public function marshal()
    {
        foreach($this->expired() as $model)
        {
            craft()->postmaster->send($model);
        }
    }

    public function expired()
    {
        $data = $this->query()->where('sendDate <= :date', array(':date' => new DateTime()));

        return $this->_populateModelsFromArray($data->queryAll());
    }

    protected function _populateModelsFromArray(Array $array)
    {
        $models = array();

        foreach($array as $row)
        {
            $model = json_decode($row['model'], true);
            $model = ModelHelper::expandModelsInArray($model);
            
            $class = $model['service']['__class__'];

            $model->service = new $class($model->service);
            $model->queueId = $row['id'];

            $models[] = new Postmaster_TransportModel($model);
        }

        return $models;
    }
}
