<?php
namespace Craft;

class Postmaster_QueueRecord extends BaseRecord
{
    public function getTableName()
    {
        return 'postmasterqueue';
    }

    protected function defineAttributes()
    {
        return array(
            'model'  => array(AttributeType::Mixed, 'column' => ColumnType::LongText),
            'sendDate'  => array(AttributeType::Mixed, 'column' => ColumnType::LongText),
        );
    }
}