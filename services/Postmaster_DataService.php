<?php
namespace Craft;

class Postmaster_DataService extends BaseApplicationComponent
{
	public function getSentMessagesCount($start = false, $end = false)
    {
        $record = new Postmaster_TransportResponseRecord;

        $query = craft()->db->createCommand()
            ->select('count(id) count')
            ->from($record->getTableName())
            ->andWhere('success = 1');

        if($start)
        {
            $query->andWhere('dateCreated >= :start', array('start' => $start));
        }

        if($end)
        {
            $query->andWhere('dateCreated <= :end', array('end' => $end));
        }

        $data = $query->queryRow();

        return (int) $data['count'];
    }   

    public function getFailedMessagesCount($start = false, $end = false)
    {
        $record = new Postmaster_TransportResponseRecord;

        $query = craft()->db->createCommand()
            ->select('count(id) count')
            ->from($record->getTableName())
            ->andWhere('success = 0');

        if($start)
        {
            $query->andWhere('dateCreated >= :date', array('date' => $start));
        }

        if($end)
        {
            $query->andWhere('dateCreated <= :date', array('date' => $end));
        }


        $data = $query->queryRow();

        return (int) $data['count'];
    }

    public function getSentParcels()
    {
        $record = new Postmaster_ParcelSentRecord;

        $query = craft()->db->createCommand()
            ->select('*, count(dateCreated) count')
            ->from($record->getTableName())
            ->group('dateCreated');

        return $query->queryAll();
    }

    public function getSentNotifications()
    {
        $record = new Postmaster_NotificationSentRecord;

        $query = craft()->db->createCommand()
            ->select('*, count(dateCreated) count')
            ->from($record->getTableName())
            ->group('dateCreated');

        return $query->queryAll();
    }
}
