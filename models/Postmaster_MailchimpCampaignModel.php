<?php
namespace Craft;

use Guzzle\Http\Client;

class Postmaster_MailchimpCampaignModel extends Postmaster_MailchimpApiModel
{
    public function create()
    {
        $data = array(
            'apikey' => $this->apiKey,
            'type' => $this->type,
            'options' => array(
                'list_id' => $this->listId,
                'subject' => $this->subject,
                'from_email' => $this->fromEmail,
                'from_name' => $this->fromName,
                'to_name' => $this->toName,
                'tracking' => array(
                    'opens' => $this->trackOpens,
                    'html_clicks' => $this->trackHtmlClicks,
                    'text_clicks' => $this->trackTextClicks
                ),
                'title' => $this->title,
                'analytics' => array(
                    'google' => $this->googleAnalytics
                )
            ),
            'content' => array(
                'html' => $this->html,
                'text' => $this->text
            )
        );

        $client = new Client();

        $request = $client->post($this->getApiUrl('campaigns/create'), array(), $data);

        try
        {
            $response = $request->send();
            $response = json_decode($response->getBody());

            $this->id = $response->id;

            return $response;
        }
        catch(\Exception $e)
        {
            throw $e;
        }
    }

    public function send()
    {
        $client = new Client();

        $request = $client->post($this->getApiUrl('campaigns/send'), array(), array(
            'apikey' => $this->apiKey,
            'cid' => $this->id
        ));

        try
        {
            $response = $request->send();

            return $response;
        }
        catch(\Exception $e)
        {
            throw $e;
        }
    }

    protected function defineAttributes()
    {
        return array(
            'id' => array(AttributeType::String),
            'apiKey' => array(AttributeType::String),
            'type' => array(AttributeType::String, 'default' => 'regular'),
            'listId' => array(AttributeType::String),
            'subject' => array(AttributeType::String),
            'fromEmail' => array(AttributeType::String),
            'fromName' => array(AttributeType::String),
            'toName' => array(AttributeType::String),
            'listId' => array(AttributeType::String),
            'title' => array(AttributeType::String),
            'html' => array(AttributeType::String),
            'text' => array(AttributeType::String),
            'trackOpens' => array(AttributeType::Bool, 'default' => true),
            'trackHtmlClicks' => array(AttributeType::Bool, 'default' => true),
            'trackTextClicks' => array(AttributeType::Bool, 'default' => true),
            'googleAnalytics' => array(AttributeType::String),
        );
    }
}