<?php
namespace Craft\Plugins\Postmaster\Responses;

use Craft\Postmaster_TransportModel;
use Craft\Plugins\Postmaster\Components\BaseResponse;

class TransportResponse extends BaseResponse {

	protected $model;

	public function __construct(Postmaster_TransportModel $model, $success, $errors = array())
	{
		parent::__construct($success, $errors);

		$this->model = $model;
	}

	public function setModel(BaseModel $model)
	{
		$this->model = $model;
	}

	public function getModel()
	{
		return $this->model;
	}
}