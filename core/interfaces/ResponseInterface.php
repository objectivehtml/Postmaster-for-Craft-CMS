<?php
namespace Craft\Plugins\Postmaster\Interfaces;

interface ResponseInterface {

	public function __toString();

	public function getSuccess();

	public function setSuccess($value);

	public function setErrors(Array $errors = array());

	public function getErrors();

	public function getCode();

	public function setCode($code);

	public function addError($error);

	public function addErrors(Array $errors = array());

	public function save();

}