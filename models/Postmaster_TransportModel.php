<?php
namespace Craft;

use Craft\Plugins\Postmaster\Interfaces\TransportInterface;

class Postmaster_TransportModel extends BaseModel implements TransportInterface
{
	public function getSendDate()
	{
		$specific = new DateTime($this->sendDateSpecific);

		if(!empty($this->sendDateRelative))
		{
			$specific->modify($this->sendDateRelative);
		}

		return $specific;
	}

	public function shouldSend()
	{
		return $this->getSendDate() <= new DateTime();
	}

	protected function defineAttributes()
    {
        return array(
            'service' => AttributeType::Mixed,
            'settings' => AttributeType::Mixed,
            'data' => AttributeType::Mixed,
            'sendDateSpecific' => array(AttributeType::String, 'default' => null),
            'sendDateRelative' => AttributeType::String,
            'queueId' => array(AttributeType::Mixed, 'default' => false)
        );
    }
}