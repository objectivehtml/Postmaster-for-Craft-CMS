<?php
namespace Craft;

class PostmasterHelper
{
	public static function hasExtraConditionals($extraConditionals)
	{
        if($extraConditionals && !empty(trim($extraConditionals)))
        {
            return true;
        }

        return false;
	}

	public static function validateExtraConditionals($extraConditionals)
	{
		return trim(strtolower($extraConditionals)) !== 'false';
	}
}