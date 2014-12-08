<?php
namespace Craft\Plugins\Postmaster\Components;

use Craft\BaseApplicationComponent;
use Craft\Plugins\Postmaster\Interfaces\EventInterface;

abstract class BaseEvent extends Base implements EventInterface {

	protected $model;

	public function getTabs()
	{
		return array();
	}

	public function getModel()
	{
		return $this->model;
	}

	public function setModel($model)
	{
		$this->model = $model;
	}
}