<?php
namespace Craft;

class m141213_150000_postmaster_parcels_sent_table extends BaseMigration
{
	public function safeUp()
	{
		$this->createTable('postmasterparcelssent', array(
            'parcelId' => array(AttributeType::Number, 'column' => ColumnType::Int, 'null' => false),
        ));

		$this->addForeignKey('postmasterparcelssent', 'parcelId', 'postmasterparcels', 'id', 'CASCADE');

		return true;
	}
}