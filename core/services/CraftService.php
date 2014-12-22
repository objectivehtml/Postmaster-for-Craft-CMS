<?php
namespace Craft\Plugins\Postmaster\Services;

use Craft\Craft;
use Craft\EmailModel;
use Craft\Postmaster_TransportModel;
use Craft\Plugins\Postmaster\Components\BaseService;

class CraftService extends BaseService {

	protected $requireModels = array(
		'Craft\EmailModel'
	);

    public function getName()
    {
        return Craft::t('Craft');
    }

    public function getId()
    {
        return 'craft';
    }

	public function send(Postmaster_TransportModel $model)
	{
		$emailModel = new EmailModel($model->settings->getAttributes());
		$emailModel->cc = explode(',', $model->settings->cc);
		$emailModel->bcc = explode(',', $model->settings->bcc);

		$success = $this->craft()->email->sendEmail($emailModel);

		if($success)
		{
			return $this->success($model);
		}
		else
		{
			return $this->failed($model, 400);
		}
	}

	public function getInputHtml(Array $data = array())
	{
		return $this->craft()->templates->render('postmaster/services/craft/settings', $data);
	}

	public function getSettingsModelClassName()
	{
		return '\Craft\Postmaster_ServiceSettingsModel';
	}

}