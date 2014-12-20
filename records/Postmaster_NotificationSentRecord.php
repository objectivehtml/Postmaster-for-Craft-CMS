<?php
namespace Craft;

class Postmaster_NotificationSentRecord extends BaseRecord
{
    public function getTableName()
    {
        return 'postmasternotificationssent';
    }

    public function defineRelations()
    {
        return array(
            'notification' => array(static::BELONGS_TO, 'Postmaster_NotificationRecord', 'required' => true, 'onDelete' => static::CASCADE),
        );
    }

    protected function defineAttributes()
    {
        return array(
            'notificationId' => array(AttributeType::Number, 'column' => ColumnType::Int),
            'senderId' => array(AttributeType::Number, 'column' => ColumnType::Int)
        );
    }
}