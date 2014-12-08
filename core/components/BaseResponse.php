<?php
namespace Craft\Plugins\Postmaster\Components;

use Craft\Postmaster_TransportResponseRecord;
use Craft\Plugins\Postmaster\Interfaces\ResponseInterface;

abstract class BaseResponse extends Base implements ResponseInterface {
	
	protected $success = false;

	protected $errors = array();

	public function __construct($success, $errors = array())
	{
		if(is_array($success))
		{
			foreach($success as $index => $value)
			{
				$this->$index = $value;
			}
		}
		else
		{
			$this->success = $success;
			$this->errors = $errors;
		}
	}

	public function __toString()
	{
		return $this->success;
	}

	public function getSuccess()
	{
		return $this->success;
	}

	public function setSuccess($value)
	{
		$this->success = $value;
	}

	public function setErrors(Array $errors = array())
	{
		$this->errors = $errors;
	}

	public function getErrors()
	{
		return $this->errors;
	}

	public function save()
	{
		$record = new Postmaster_TransportResponseRecord();
        $record->success = $this->getSuccess();
        $record->errors = $this->getErrors();
        $record->model = $this->model->getAttributes();
        $record->service = $this->model->service->name;

        $record->save();
	}
}