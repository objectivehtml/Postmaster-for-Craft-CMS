<?php
namespace Craft;

class m191214_121800_postmaster_add_sender_id_to_notifications_sent_table extends BaseMigration
{
	public function safeUp()
	{
		$table = new Postmaster_NotificationSentRecord;

		$this->addColumnAfter($table->getTableName(), 'senderId', ColumnType::Int, 'notificationId');

		return true;
	}
}