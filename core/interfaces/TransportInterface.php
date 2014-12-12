<?php
namespace Craft\Plugins\Postmaster\Interfaces;

interface TransportInterface {
	
	public function getSendDate();

	public function shouldSend();
	
}