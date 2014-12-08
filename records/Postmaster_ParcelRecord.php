<?php
namespace Craft;

class Postmaster_ParcelRecord extends BaseRecord
{
    public function getTableName()
    {
        return 'postmasterparcels';
    }

    protected function defineAttributes()
    {
        return array(
            'title'    => array(AttributeType::String, 'column' => ColumnType::Text),
            'settings' => array(AttributeType::String, 'column' => ColumnType::LongText),
            'enabled'  => array(AttributeType::Bool, 'column' => ColumnType::Int, 'default' => 1),
        );
    }
}