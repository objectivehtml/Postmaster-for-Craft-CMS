<?php
namespace Craft;

class Postmaster_TemplateController extends BaseController
{
	public function actionGetHtml()
	{
		$id = craft()->request->getSegment(4);

		$record = Postmaster_MessageTemplateRecord::model()->findById($id);

		craft()->path->setTemplatesPath(CRAFT_BASE_PATH.'plugins/postmaster/templates');

		$this->renderTemplate('_messageTemplate', array(
			'template' => $record->html
		));
	}

	public function actionGetText()
	{
		$id = craft()->request->getSegment(4);

		$record = Postmaster_MessageTemplateRecord::model()->findById($id);

		craft()->path->setTemplatesPath(CRAFT_BASE_PATH.'plugins/postmaster/templates');

		$this->renderTemplate('_messageTemplate', array(
			'template' => $record->plain
		));
	}
}