<?php
namespace Craft;

class Postmaster_MailchimpApiModel extends BaseModel
{
    protected function getApiUrl($method)
    {
        $url = 'https://<dc>.api.mailchimp.com/2.0/'.$method.'.json';
        
        return str_replace('<dc>', substr($this->apiKey, strpos($this->apiKey, '-')+1), $url);
    }

	protected function defineAttributes()
    {
        return array();
    }
}