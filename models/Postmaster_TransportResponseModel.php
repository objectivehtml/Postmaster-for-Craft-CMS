<?php
namespace Craft;

use Craft\Plugins\Postmaster\Interfaces\ResponseInterface;

class Postmaster_TransportResponseModel extends BaseModel implements ResponseInterface
{
	public function __toString()
	{
		return $this->success;
	}

	public function getModel()
	{
		return $this->model;
	}

	public function setModel($model)
	{
		$this->model = $model;
	}

	public function getSuccess()
	{
		return (bool) $this->success;
	}

	public function setSuccess($value)
	{
		$this->success = (bool) $value;
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
		$errors = $this->errors;
		$errors[] = $error;
		$this->errors = $errors;
	}

	public function save()
	{
		if(!empty($this->model->queueId))
		{
			return;
		}

		$record = new Postmaster_TransportResponseRecord();
        $record->success = $this->getSuccess();
        $record->errors = $this->getErrors();
        $record->code = $this->getCode();
        $record->model = $this->model;
        $record->service = $this->model->service->getName();

        $record->save();
	}

	protected function defineAttributes()
    {
        return array(
            'success' => array(AttributeType::Bool, 'default' => true),
            'code' => array(AttributeType::Number, 'default' => 200),
            'errors' => array(AttributeType::Mixed, 'default' => array()),
            'service' => AttributeType::String,
            'model' => AttributeType::Mixed,
            // 'senderId' => AttributeType::String,
            'dateCreated' => array(AttributeType::Mixed, 'default' => DateTimeHelper::formatTimeForDb())
        );
    }
}