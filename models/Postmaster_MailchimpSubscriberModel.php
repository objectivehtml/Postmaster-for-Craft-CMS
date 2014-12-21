<?php
namespace Craft;

use Craft\Plugins\Postmaster\Responses\TransportResponse;
use Guzzle\Http\Client;

class Postmaster_MailchimpSubscriberModel extends Postmaster_MailchimpApiModel
{
    public function subscribe()
    {
        $client = new Client();

        $mergeVars = array();

        if(!empty($this->newEmail))
        {
            $mergeVars['new-email'] = $this->newEmail;
        }

        if(count($this->groupings))
        {
            $mergeVars['groupings'] = $this->groupings;
        }

        if(!empty($this->optinIp))
        {
            $mergeVars['optin_ip'] = $this->optinIp;
        }

        if(!empty($this->optinTime))
        {
            $mergeVars['optin_time'] = $this->optinTime;
        }

        if(!empty($this->latitude) && !empty($this->latitude))
        {
            $mergeVars['mc_location'] = array(
                'latitude' => $this->latitude,
                'longitude' => $this->longitude
            );
        }

        if(!empty($this->language))
        {
            $mergeVars['mc_language'] = $this->language;
        }

        if(count($this->notes))
        {
            $mergeVars['mc_notes'] = $this->notes;
        }

        if(isset($this->profileVars[0]['value']) && !empty($this->profileVars[0]['value']))
        {
            $mergeVars['fname'] = $this->profileVars[0]['value'];
        }

        if(isset($this->profileVars[1]['value']) && !empty($this->profileVars[1]['value']))
        {
            $mergeVars['lname'] = $this->profileVars[1]['value'];
        }

        if(isset($this->profileVars[8]['value']) && !empty($this->profileVars[9]['value']))
        {
            $mergeVars['date'] = $this->profileVars[8]['value'];
        }

        if(isset($this->profileVars[9]['value']) && !empty($this->profileVars[9]['value']))
        {
            $mergeVars['birthday'] = $this->profileVars[9]['value'];
        }

        if(isset($this->profileVars[10]['value']) && !empty($this->profileVars[10]['value']))
        {
            $mergeVars['phone'] = $this->profileVars[10]['value'];
        }

        if(isset($this->profileVars[11]['value']) && !empty($this->profileVars[11]['value']))
        {
            $mergeVars['website'] = $this->profileVars[11]['value'];
        }

        if($this->hasAddress())
        {
            $mergeVars['address'] = $this->getAddress();
        }

        $data = array(
            'apikey' => $this->apiKey,
            'id' => $this->listId,
            'email' => array(
                'email' => $this->email
            ),
            'merge_vars' => $mergeVars,
            'email_type' => $this->type,
            'double_optin' => $this->doubleOptin,
            'update_existing' => $this->updateExisting,
            'replace_interests' => $this->replaceInterests,
            'send_welcome' => $this->sendWelcome
        );

        $request = $client->post($this->getApiUrl('lists/subscribe'), array(), $data);

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
            'apikey' => $this->apiKey,
            'id' => $this->listId,
            'email' => array(
                'email' => $this->email,
            ),
            'delete_member' => (bool) $this->deleteMember,
            'send_goodbye' => (bool) $this->sendGoodbye,
            'send_notify' => (bool) $this->sendNotify,
        );

        $request = $client->post($this->getApiUrl('lists/unsubscribe'), array(), $data);

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

    public function getAddress()
    {
        return array(
            'addr1' => isset($this->profileVars[2]) ? $this->profileVars[2]['value'] : '',
            'addr2' => isset($this->profileVars[3]) ? $this->profileVars[3]['value'] : '',
            'city' => isset($this->profileVars[4]) ? $this->profileVars[4]['value'] : '',
            'state' => isset($this->profileVars[5]) ? $this->profileVars[5]['value'] : '',
            'zip' => isset($this->profileVars[6]) ? $this->profileVars[6]['value'] : '',
            'country' => isset($this->profileVars[7]) ? $this->profileVars[7]['value'] : '',
        );
    }

    public function hasAddress()
    {
        if(isset($this->profileVars[2]) && !empty($this->profileVars[2]['value']))
        {
            return true;
        }

        if(isset($this->profileVars[3]) && !empty($this->profileVars[3]['value']))
        {
            return true;
        }

        if(isset($this->profileVars[4]) && !empty($this->profileVars[4]['value']))
        {
            return true;
        }

        if(isset($this->profileVars[5]) && !empty($this->profileVars[5]['value']))
        {
            return true;
        }

        if(isset($this->profileVars[6]) && !empty($this->profileVars[6]['value']))
        {
            return true;
        }

        if(isset($this->profileVars[7]) && !empty($this->profileVars[7]['value']))
        {
            return true;
        }
    }

	protected function defineAttributes()
    {
        return array(
            'apiKey' => array(AttributeType::String),
            'listId' => array(AttributeType::String),
            'type' => array(AttributeType::String, 'default' => 'html'),
            'email' => array(AttributeType::String),
            'newEmail' => array(AttributeType::String),
            'groupings' => array(AttributeType::Mixed, 'default' => array()),
            'optinIp' => array(AttributeType::String),
            'optinTime' => array(AttributeType::String),
            'latitude' => array(AttributeType::String),
            'longitude' => array(AttributeType::String),
            'language' => array(AttributeType::String),
            'notes' => array(AttributeType::Mixed, 'default' => array()),
            'doubleOptin' => array(AttributeType::Bool, 'default' => true),
            'deleteMember' => array(AttributeType::Bool, 'default' => false),
            'sendGoodbye' => array(AttributeType::Bool, 'default' => true),
            'sendNotify' => array(AttributeType::Bool, 'default' => true),
            'updateExisting' => array(AttributeType::Bool, 'default' => true),
            'replaceInterests' => array(AttributeType::Bool, 'default' => false),
            'sendWelcome' => array(AttributeType::Bool, 'default' => false),
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