<?php
namespace Craft;

use Craft\Plugins\Postmaster\Responses\TransportResponse;
use Guzzle\Http\Client;

class Postmaster_CampaignMonitorSubscriberModel extends Postmaster_CampaignMonitorApiModel
{
    public function subscribe()
    {
        $client = new Client();

        $data = array(
            'EmailAddress' => $this->email,
            'Name' => $this->name,
            'CustomFields' => $this->customFields,
            'Resubscribe' => (bool) (int) $this->resubscribe
        );

        $request = $client->post($this->getApiUrl('subscribers/'.$this->listId), array(), json_encode($data));
        $request->setAuth($this->apiKey, '');

        try
        {
            $response = $request->send();
            $response = json_decode($response->getBody());

            return $response;
        }
        catch(\Exception $e)
        {
            throw $e;
        }
    }

    public function unsubscribe()
    {
        $client = new Client();

        $data = array(
            'EmailAddress' => $this->email
        );

        $request = $client->post($this->getApiUrl('subscribers/'.$this->listId.'/unsubscribe'), array(), json_encode($data));
        $request->setAuth($this->apiKey, '');

        try
        {
            $response = $request->send();
            $response = json_decode($response->getBody());

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
            'apiKey' => array(AttributeType::String),
            'listId' => array(AttributeType::String),
            'email' => array(AttributeType::String),
            'name' => array(AttributeType::String),
            'customFields' => array(AttributeType::Mixed, 'default' => array()),
            'resubscribe' => array(AttributeType::Bool, 'default' => 0)
        );
    }
}