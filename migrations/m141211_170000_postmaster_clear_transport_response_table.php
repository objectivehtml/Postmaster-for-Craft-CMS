<?php
namespace Craft;

class m141211_170000_postmaster_clear_transport_response_table extends BaseMigration
{
	public function safeUp()
	{
		$table = new Postmaster_TransportResponseRecord;

		$this->addColumnAfter($table->getTableName(), 'code', ColumnType::Text, 'success');

		foreach(Postmaster_TransportResponseRecord::model()->findAll() as $row)
		{
			if(!isset($row->model['service']['__class__']))
			{
				$obj = craft()->postmaster->getRegisteredService($row->model['service']['id']);

				$model = $row->model;

				$service = $model['service'];
				$service['__class__'] = $obj->__class__;

				$model['service'] = $service;

				$row->model = $model;
				$row->save();
			}
		}

		return true;
	}
}