<?php
namespace Craft;

class Postmaster_ParcelSentRecord extends BaseRecord
{
    public function getTableName()
    {
        return 'postmasterparcelssent';
    }

    public function defineRelations()
    {
        return array(
            'parcel' => array(static::BELONGS_TO, 'Postmaster_ParcelRecord', 'required' => true, 'onDelete' => static::CASCADE),
        );
    }

    protected function defineAttributes()
    {
        return array(
            'parcelId' => array(AttributeType::Number, 'column' => ColumnType::Int),
        );
    }
}