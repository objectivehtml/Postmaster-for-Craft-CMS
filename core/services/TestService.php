<?php
namespace Craft\Plugins\Postmaster\Services;

use Craft\Craft;
use Craft\Postmaster_TransportModel;
use Craft\Plugins\Postmaster\Components\BaseService;

class TestService extends BaseService {

    public function getName()
    {
        return Craft::t('Test Email');
    }

    public function getId()
    {
        return 'testing';
    }

	public function send(Postmaster_TransportModel $model)
	{
		if($this->settings->status == 'success')
		{
			return $this->success($model, $this->settings->code);
		}
		else
		{
			foreach($this->settings->errors as $error)
			{
				$errors[] = $error['error'];
			}

			return $this->failed($model, $this->settings->code, $errors);
		}
	}

	public function getInputHtml(Array $data = array())
	{
		return $this->craft()->templates->render('postmaster/services/testing/settings', $data);
	}

	public function getSettingsModelClassName()
	{
		return '\Craft\Postmaster_TestServiceSettingsModel';
	}
}