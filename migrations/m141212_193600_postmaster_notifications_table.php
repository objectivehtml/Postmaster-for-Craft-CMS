<?php
namespace Craft;

class m141212_193600_postmaster_notifications_table extends BaseMigration
{
	public function safeUp()
	{
		$this->createTable('postmasternotifications', array(
            'title'    => array(AttributeType::String, 'column' => ColumnType::Text),
            'settings' => array(AttributeType::String, 'column' => ColumnType::LongText),
            'enabled'  => array(AttributeType::Bool, 'column' => ColumnType::Int, 'default' => 1),
        ));

		return true;
	}
}