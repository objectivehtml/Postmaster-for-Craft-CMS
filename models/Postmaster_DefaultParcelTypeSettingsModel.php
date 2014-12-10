<?php
namespace Craft;

class Postmaster_DefaultParcelTypeSettingsModel extends Postmaster_EmailModel
{
	protected function defineAttributes()
    {
    	$attributes = parent::defineAttributes();
    	$attributes['events'] = array(AttributeType::Mixed, 'default' => array());
    	$attributes['sections'] = array(AttributeType::Mixed, 'default' => array());
    	$attributes['statuses'] = array(AttributeType::Mixed, 'default' => array());
        $attributes['triggers'] = array(AttributeType::Mixed, 'default' => array());
        $attributes['extraConditionals'] = array(AttributeType::String);

        return $attributes;
    }
}