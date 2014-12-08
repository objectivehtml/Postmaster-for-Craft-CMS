<?php
namespace Craft;

class Postmaster_TransportModel extends BaseModel
{
	protected function defineAttributes()
    {
        return array(
            'service' => AttributeType::Mixed,
            'settings' => AttributeType::Mixed,
            'data' => AttributeType::Mixed
        );
    }
}