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
            'recordId' => array(AttributeType::Number, 'column' => ColumnType::Int),
            'recordType' => array(AttributeType::String, 'column' => ColumnType::Text),
            'model'  => array(AttributeType::Mixed, 'column' => ColumnType::LongText),
            'sendDate'  => array(AttributeType::Mixed, 'column' => ColumnType::LongText),
        );
    }
}