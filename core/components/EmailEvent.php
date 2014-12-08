<?php
namespace Craft\Plugins\Postmaster\Components;

abstract class EmailEvent extends BaseEvent {
	
	public function getTabs()
	{
		return array(
		    'recipient' => array(
		    	'label' => \Craft\Craft::t('Parcel'), 
		    	'url' => '#recipient'
		    ),
		    'settings' => array(
		    	'label' => \Craft\Craft::t('Settings'), 
		    	'url' => '#settings'
		    ),
		    'service' => array(
		    	'label' => \Craft\Craft::t('Service'), 
		    	'url' => '#service'
		    )
		);
	}

	public function getInputHtml()
	{
		$data = array();

		if($this->model) {
			$data = array_merge($data, $this->model->getAttributes());
		}

		return $this->craft()->templates->render('postmaster/_emailEventSettings', $data);	
	}
}