<?php
namespace Craft;

class Postmaster_UserEmailParcelTypeSettingsModel extends Postmaster_EmailModel
{
	protected function defineAttributes()
    {
    	return array_merge(parent::defineAttributes(), array(
            'events' => array(AttributeType::Mixed, 'default' => array()),
            'triggers' => array(AttributeType::Mixed, 'default' => array()),
            'extraConditionals' => array(AttributeType::String),
    	));
    }
}