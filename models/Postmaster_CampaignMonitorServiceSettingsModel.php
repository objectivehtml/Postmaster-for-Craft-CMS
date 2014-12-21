<?php
namespace Craft;

class Postmaster_CampaignMonitorServiceSettingsModel extends Postmaster_ServiceSettingsModel
{
    protected function defineAttributes()
    {
        return array(
            'apiKey' => array(AttributeType::String),
            'clientId' => array(AttributeType::String),
            'listIds' => array(AttributeType::Mixed, 'default' => array()),
            'listId' => array(AttributeType::String),
            'action' => array(AttributeType::String),
            'name' => array(AttributeType::String),
            'segmentIds' => array(AttributeType::Mixed ,'default' => array()),
            'subscriberEmail' => array(AttributeType::String),
            'subscriberName' => array(AttributeType::String),
            'confirmationEmail' => array(AttributeType::String),
            'customFields' => array(AttributeType::Mixed, 'default' => array()),
            'resubscribe' => array(AttributeType::Bool, 'default' => 0),

            /*
            'campaignType' => array(AttributeType::String, 'default' => 'regular'),
            'subscriberType' => array(AttributeType::String, 'default' => 'html'),
            'doubleOptin' => array(AttributeType::Bool, 'default' => true),
            'updateExisting' => array(AttributeType::Bool, 'default' => true),
            'replaceInterests' => array(AttributeType::Bool, 'default' => false),
            'sendWelcome' => array(AttributeType::Bool, 'default' => false),
            'subscriberEmail' => array(AttributeType::String),
            'subscriberNewEmail' => array(AttributeType::String),
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
            */
        );
    }
}