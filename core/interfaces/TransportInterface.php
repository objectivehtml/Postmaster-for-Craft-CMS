<?php
namespace Craft\Plugins\Postmaster\Interfaces;

use Carbon\Carbon;

interface TransportInterface {

	public function shouldSend();
	
	public function getSendDate();

	public function setSendDate(Carbon $date);

}