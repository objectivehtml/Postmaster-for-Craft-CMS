<?php
namespace Craft\Plugins\Postmaster\Interfaces;

use Carbon\Carbon;

interface TransportInterface {

	public function getData($key = false);

	public function setData($value);

	public function addData($key, $value);

	public function shouldSend();
	
	public function getSendDate();

	public function setSendDate(Carbon $date);

}