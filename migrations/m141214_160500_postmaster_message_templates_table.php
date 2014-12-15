<?php
namespace Craft;

class m141214_160500_postmaster_message_templates_table extends BaseMigration
{
	public function safeUp()
	{
		$this->createTable('postmastermessagetemplates', array(
            'plain' => array(AttributeType::Mixed, 'column' => ColumnType::LongText),
            'html' => array(AttributeType::Mixed, 'column' => ColumnType::LongText),
        ));

		return true;
	}
}