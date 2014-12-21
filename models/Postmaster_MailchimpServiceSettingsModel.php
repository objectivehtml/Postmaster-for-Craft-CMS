<?php
namespace Craft;

class Postmaster_MailchimpServiceSettingsModel extends Postmaster_ServiceSettingsModel
{
	protected function defineAttributes()
    {
        return array(
        	'apiKey' => array(AttributeType::String),
            'campaignType' => array(AttributeType::String, 'default' => 'regular'),
            'subscriberType' => array(AttributeType::String, 'default' => 'html'),
            'title' => array(AttributeType::String),
            'listIds' => array(AttributeType::Mixed, 'default' => array()),
            'listId' => array(AttributeType::String),
            'action' => array(AttributeType::String),
            'doubleOptin' => array(AttributeType::Bool, 'default' => true),
            'updateExisting' => array(AttributeType::Bool, 'default' => true),
            'replaceInterests' => array(AttributeType::Bool, 'default' => false),
            'sendWelcome' => array(AttributeType::Bool, 'default' => false),
            'subscriberEmail' => array(AttributeType::String),
            'subscriberNewEmail' => array(AttributeType::String),
            'deleteMember' => array(AttributeType::Bool, 'default' => false),
            'sendGoodbye' => array(AttributeType::Bool, 'default' => true),
            'sendNotify' => array(AttributeType::Bool, 'default' => true),
            'trackOpens' => array(AttributeType::Bool, 'default' => true),
            'trackHtmlClicks' => array(AttributeType::Bool, 'default' => true),
            'trackTextClicks' => array(AttributeType::Bool, 'default' => true),
            'googleAnalytics' => array(AttributeType::String),
            'profileVars' => array(AttributeType::Mixed, 'default' => array(
                array('name' => 'lname', 'value'=> ''),
                array('name' => 'fname', 'value'=> ''),
                array('name' => 'addr1', 'value'=> ''),
                array('name' => 'addr2', 'value'=> ''),
                array('name' => 'city', 'value'=> ''),
                array('name' => 'state', 'value'=> ''),
                array('name' => 'zip', 'value'=> ''),
                array('name' => 'country', 'value'=> ''),
                array('name' => 'date', 'value'=> ''),
                array('name' => 'birthday', 'value'=> ''),
                array('name' => 'phone', 'value'=> ''),
                array('name' => 'website', 'value'=> ''),
            ))
        );
    }
}