<?php
namespace Craft;

class Postmaster_FormsService extends BaseApplicationComponent
{
    public function onEmailFormSend(Event $event)
    {
        $this->raiseEvent('onEmailFormSend', $event);

        return $event;
    }

}
