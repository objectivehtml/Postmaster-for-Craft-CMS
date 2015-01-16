<?php
namespace Craft\Plugins\Postmaster\NotificationTypes;

use Carbon\Carbon;
use Craft\Craft;
use Craft\UserRecord;
use Craft\UserModel;
use Craft\Postmaster_TransportModel;
use Craft\Postmaster_TransportResponseModel;
use Craft\Plugins\Postmaster\Components\BaseNotificationType;

class AfterUserInactivityNotificationType extends BaseNotificationType {
    
    protected $now;

    public function __construct($attributes = null)
    {
        parent::__construct($attributes);
    }

    public function getName()
    {
        return Craft::t('After User Inactivity');
    }

    public function getId()
    {
        return 'afterUserInactivity';
    }

    public function onBeforeSend(Postmaster_TransportModel $model, $debug = false)
    {
        // Get the user results as an array straight from the db
        $results = $this->_getUsers();

        // Set $this->totalResults and return false if no results
        if(($this->totalResults = count($results)) == 0)
        {
            // Return false to prevent the notification from sending
            return false;
        }

        // Create a user model from the first results.
        // Other users will be handled later in the script
        $user = UserModel::populateModel($results[0]);

        // Set the senderId to Postmaster_TransportModel instance
        $model->senderId = $user->id;

        // If the action is to fire on unchanged password
        if($this->settings->action == 'changePassword')
        {
            // If the notification type is set to force password resets
            // then update the user model in the db
            if((int) $this->settings->forcePasswordReset == 1)
            {
                $user->passwordResetRequired = true;

                $this->craft()->users->saveUser($user);
            }
        }

        $this->parse(array(
            'user' => $user
        ));

        // Return true, as the notification should send
        return true;
    }

    public function onSendComplete(Postmaster_TransportResponseModel $model)
    {
        // Since we want to send notifications to all users we need
        // to test to see if $this->totalResults is greater than 1.
        // If saw, trigger the parent notification's marshal() method.
        // This will ensure the notification fires again until it either
        // times out, or there are no more users.
        if($this->totalResults > 1)
        {
            $this->notification->marshal();
        }
    }

    public function getInputHtml(Array $data = array())
    {
        return $this->craft()->templates->render('postmaster/notification_types/after_user_inactivity/fields', $data);
    }

    public function getSettingsInputHtml(Array $data = array())
    {
        return $this->craft()->templates->render('postmaster/notification_types/after_user_inactivity/settings', $data);
    }

    public function getSettingsModelClassName()
    {
        return '\Craft\Postmaster_AfterUserInactivityNotificationTypesSettingsModel';
    }

    private function _getUsers()
    {
        $record = new UserRecord();

        $tz = $this->craft()->getTimezone();

        $sendDate = Carbon::now($tz);

        if($this->settings->action == 'changePassword')
        {
            $field = 'lastPasswordChangeDate';

            if(!empty($this->settings->passwordElapsedTime))
            {
                $sendDate->modify($this->settings->passwordElapsedTime);
            }
        }
        else
        {
            $field = 'lastLoginDate';

            if(!empty($this->settings->loginElapsedTime))
            {
                $sendDate->modify($this->settings->loginElapsedTime);
            }
        }

        $query = $this->craft()->db->createCommand()
            ->select('{{users}}.*, count(notifications.id) as \'count\', notifications.dateCreated as \'lastSent\'')
            ->from($record->getTableName())
            ->leftJoin('usergroups_users', '{{users}}.id = {{usergroups_users}}.userId')
            ->leftJoin('(SELECT * FROM {{postmasternotificationssent}} WHERE {{postmasternotificationssent}}.notificationId = '.$this->notification->id.' ORDER BY dateCreated DESC) notifications', '{{users}}.id = notifications.senderId')
            ->group('{{users}}.id')
            ->having(array('and', 'lastSent <= :date or lastSent is null'), array(
                'date' => $sendDate
            ))
            ->andWhere(array('or', '{{users}}.'.$field.' <= :date and {{users}}.'.$field.' is not null', '{{users}}.'.$field.' is null and {{users}}.dateCreated <= :date'), array(
                'date' => $sendDate
            ))
            ->order('notifications.dateCreated desc');

        if($this->settings->action == 'changePassword')
        {
            $query->andWhere('{{users.passwordResetRequired}} = 0');
        }
        else
        {
            // $query->andWhere('{{users}}.lastLoginDate is not null');
        }

        if(is_array($this->settings->types))
        {
            $where = array();

            foreach($this->settings->types as $type)
            {
                $where[] = '{{users}}.' . $type . ' = 1';
            }

            $query->andWhere(array('and', implode(' or ', $where)));
        }

        if(is_array($this->settings->groups))
        {
            foreach($this->settings->groups as $group)
            {
                $query->andWhere('{{usergroups_users}}.groupId = :group', array(':group' => $group));
            }
        }

        $results = $query->queryAll();

        return $results;
    }
}