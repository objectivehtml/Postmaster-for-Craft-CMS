<?php
namespace Craft;

class Postmaster_TestServiceSettingsModel extends Postmaster_ServiceSettingsModel
{
	protected function defineAttributes()
    {
        return array(
        	'status' => array(AttributeType::String, 'default' => 'success'),
        	'code' => array(AttributeType::Number, 'default' => 200),
        	'errors' => array(AttributeType::Mixed, 'default' => array()),
        );
    }
}