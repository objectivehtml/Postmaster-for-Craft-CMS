<?php

function postmaster_autoload($class)
{
	if(preg_match("/^Craft\\\Plugins\\\Postmaster/", $class))
	{
		$paths = str_replace('Craft\Plugins\Postmaster\\', '', $class);
		$paths = explode('\\', $paths);

		foreach(preg_split('/(?=[A-Z])/', $paths[0]) as $dir_part)
		{
			if(!empty($dir_part))
			{
				$dir[] = strtolower($dir_part);
			}
		}

		if(isset($dir))
		{
			$dir = implode('_', $dir);
		}
		else
		{
			$dir = strtolower($paths[0]);
		}

		require_once 'core/' . $dir . '/' . $paths[1] . '.php';
	}

}

spl_autoload_register('postmaster_autoload');