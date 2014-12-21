<?php
namespace Craft;

class PostmasterHelper
{
	public static function hasExtraConditionals($extraConditionals)
	{
        if($extraConditionals && !empty($extraConditionals))
        {
            return true;
        }

        return false;
	}

	public static function validateExtraConditionals($extraConditionals)
	{
		return strtolower($extraConditionals) !== 'false';
	}
}