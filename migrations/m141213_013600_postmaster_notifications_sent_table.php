<?php
namespace Craft;

class m141213_013600_postmaster_notifications_sent_table extends BaseMigration
{
	public function safeUp()
	{
		$this->createTable('postmasternotificationssent', array(
            'notificationId' => array(AttributeType::Number, 'column' => ColumnType::Int, 'null' => false),
        ));

		$this->addForeignKey('postmasternotificationssent', 'notificationId', 'postmasternotifications', 'id', 'CASCADE');

		return true;
	}
}