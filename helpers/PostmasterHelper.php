<?php
namespace Craft;

class PostmasterHelper
{
	public static function hasExtraConditionals($extraConditionals)
	{
		$extraConditionals = trim($extraConditionals);

        if($extraConditionals && !empty($extraConditionals))
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