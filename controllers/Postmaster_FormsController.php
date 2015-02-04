<?php
namespace Craft;

class Postmaster_FormsController extends BaseController
{
	protected $allowAnonymous = true;

	public function actionSend()
	{
		$model = new Postmaster_EmailFormModel(craft()->request->getPost());

		if(!$model->validate())
		{ 
			// Prepare a flash error message for the user.
            craft()->userSession->setError(Craft::t('The email could not be sent.'));

            craft()->urlManager->setRouteVariables(array(
            	'email' => $model
            ));
		}
		else
		{
			craft()->postmaster_forms->onEmailFormSend(new Event($this, array(
				'email' => $model
			)));

			$this->redirect(craft()->request->getPost('redirect'));
		}
	}
}