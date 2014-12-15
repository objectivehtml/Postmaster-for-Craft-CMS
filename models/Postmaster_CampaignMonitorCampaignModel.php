<?php
namespace Craft;

use Guzzle\Http\Client;

class Postmaster_CampaignMonitorCampaignModel extends Postmaster_CampaignMonitorApiModel
{
    public function create()
    {
        $url = craft()->postmaster->createTemplateUrls($this->body, $this->htmlBody);

        $data = array(
            'Name' => $this->name,
            'Subject' => $this->subject,
            'FromName' => $this->fromName,
            'FromEmail' => $this->fromEmail,
            'ReplyTo' => !empty($this->replyTo) ? $this->replyTo : $this->fromEmail,
            'ListIDs' => $this->listIds,
            'SegmentIDs' => $this->segmentIds,
            'TextUrl' => $url['text'],
            'HtmlUrl' => $url['html']
        );

        $client = new Client();

        $request = $client->post($this->getApiUrl('campaigns/' . $this->clientId), array(), json_encode($data));
        $request->setAuth($this->apiKey, '');

        try
        {
            $response = $request->send();
            $response = json_decode($response->getBody());

            $this->id = $response;

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

        $url = $this->getApiUrl('campaigns/' . $this->id . '/send');

        $data = array(
            'ConfirmationEmail' => !empty($this->confirmationEmail) ? $this->confirmationEmail : $this->fromEmail,
            'SendDate' => $this->sendDate
        );

        $request = $client->post($url, array(), json_encode($data));
        $request->setAuth($this->apiKey, '');

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
            'name' => array(AttributeType::String),
            'apiKey' => array(AttributeType::String),
            'clientId' => array(AttributeType::String),
            'listIds' => array(AttributeType::Mixed, 'default' => array()),
            'segmentIds' => array(AttributeType::Mixed, 'default' => array()),
            'subject' => array(AttributeType::String),
            'fromEmail' => array(AttributeType::String),
            'fromName' => array(AttributeType::String),
            'confirmationEmail' => array(AttributeType::String),
            'replyTo' => array(AttributeType::String),
            'htmlBody' => array(AttributeType::String),
            'body' => array(AttributeType::String),
            'sendDate' => array(AttributeType::String, 'default' => \Carbon\Carbon::now(craft()->getTimezone())->format('Y-m-d H:i'))
        );
    }
}