<?php
namespace Craft;

class Postmaster_TransportResponseRecord extends BaseRecord
{
    public function getTableName()
    {
        return 'postmastertransportresponses';
    }

    protected function defineAttributes()
    {
        return array(
            'success' => array(AttributeType::Bool, 'column' => ColumnType::Int),
            'errors' => array(AttributeType::Mixed, 'column' => ColumnType::LongText),
            'code' => array(AttributeType::String, 'column' => ColumnType::Text),
            'service' => array(AttributeType::Mixed, 'column' => ColumnType::Text),
            'model' => array(AttributeType::Mixed, 'column' => ColumnType::LongText),
        );
    }
}