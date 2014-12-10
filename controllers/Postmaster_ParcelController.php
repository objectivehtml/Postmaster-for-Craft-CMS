<?php
namespace Craft;

class Postmaster_ParcelController extends BaseController
{
	public function actionCreateParcel()
	{
		$model = new Postmaster_ParcelModel();

		$parcel = new Plugins\Postmaster\Components\Parcel(array(
			'model' => $model
		));

		craft()->templates->includeJsResource('postmaster/js/postmaster.js');

        $this->renderTemplate('postmaster/_parcel', array(
        	'header' => Craft::t('New Parcel'),
        	'parcel' => $parcel
        ));
	}

	public function actionEditParcel()
	{
		$id = craft()->request->getSegment(3);
		
		$model = craft()->postmaster_parcels->findById($id);

		$parcel = new Plugins\Postmaster\Components\Parcel(array(
			'model' => $model
		));

		craft()->templates->includeJsResource('postmaster/js/postmaster.js');

        $this->renderTemplate('postmaster/_parcel', array(
        	'header' => Craft::t('Edit Parcel'),
        	'parcel' => $parcel
        ));
	}

	public function actionDeleteParcel()
	{
		$id = craft()->request->getSegment(4);
		
		craft()->postmaster_parcels->delete($id);

		$this->redirect('postmaster');
	}

	public function actionSaveParcel()
	{
		$this->requirePostRequest();

		$record = craft()->postmaster_parcels->create(array(
			'title' => craft()->request->getPost('title'),
			'enabled' => craft()->request->getPost('enabled'),
			'settings' => craft()->request->getPost('settings')
		));

		$this->redirect('postmaster');		
	}

	public function actionUpdateParcel()
	{
		$this->requirePostRequest();
	
		$id = craft()->request->getSegment(3);

		$record = craft()->postmaster_parcels->update($id, array(
			'title' => craft()->request->getPost('title'),
			'enabled' => craft()->request->getPost('enabled'),
			'settings' => craft()->request->getPost('settings')
		));

		$this->redirect('postmaster');		
	}

}