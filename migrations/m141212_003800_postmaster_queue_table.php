<?php
namespace Craft;

class m141212_003800_postmaster_queue_table extends BaseMigration
{
	public function safeUp()
	{
		$this->createTable('postmasterqueue', array(
            'model'  => array(AttributeType::Mixed, 'column' => ColumnType::LongText),
            'sendDate'  => array(AttributeType::Mixed, 'column' => ColumnType::LongText),
        ));

		return true;
	}
}