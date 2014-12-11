<?php
namespace Craft;

class m141211_074724_postmaster_remove_ping_service_from_parcels extends BaseMigration
{
	public function safeUp()
	{
		$record = new Postmaster_ParcelRecord();

		$query = craft()->db->createCommand()
        	->select('*')
        	->from($record->getTableName());

        foreach($query->queryAll() as $row)
        {
        	$settings = json_decode($row['settings']);

	        $record = Postmaster_ParcelRecord::model()->findById($row['id']);

        	if($settings->service != 'Craft\Plugins\Postmaster\Services\PingService')
        	{
        		unset($settings->serviceSettings->ping);

	        	$record->settings = json_encode($settings);
	        	$record->save();
	        }
	        else
	        {
	        	$record->delete();
	        }
        }

		return true;
	}
}