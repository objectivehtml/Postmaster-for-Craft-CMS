<?php
namespace Craft;

class Postmaster_MessageTemplateRecord extends BaseRecord
{
    public function getTableName()
    {
        return 'postmastermessagetemplates';
    }

    protected function defineAttributes()
    {
        return array(
            'plain' => array(AttributeType::Mixed, 'column' => ColumnType::LongText),
            'html' => array(AttributeType::Mixed, 'column' => ColumnType::LongText),
        );
    }
}