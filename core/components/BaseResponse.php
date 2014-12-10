<?php
namespace Craft\Plugins\Postmaster\Components;

use Craft\Postmaster_TransportResponseRecord;
use Craft\Plugins\Postmaster\Interfaces\ResponseInterface;

abstract class BaseResponse extends Base implements ResponseInterface {
	
	protected $success = false;

	protected $errors = array();

	protected $code = 200;

	public function __construct($success, $errors = array(), $code = 200)
	{
		if(is_array($success))
		{
			parent::__construct($success);
		}
		else
		{
			$this->success = $success;
			$this->errors = $errors;
			$this->code = $code;
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

	public function getCode()
	{
		return $this->code;
	}

	public function setCode($code)
	{
		$this->code = $code;
	}

	public function setErrors(Array $errors = array())
	{
		$this->errors = $errors;
	}

	public function getErrors()
	{
		return $this->errors;
	}

	public function addErrors(Array $errors = array())
	{
		foreach($errors as $error)
		{
			$this->addError($error);
		}
	}

	public function addError($error)
	{
		$this->errors[] = $error;
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