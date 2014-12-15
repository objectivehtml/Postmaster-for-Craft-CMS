<?php
namespace Craft;

use Twig_Extension;  
use Twig_Filter_Method;

class PostmasterTwigExtension extends \Twig_Extension  
{
    public function getName()
    {
        return 'Postmaster';
    }

	public function getFilters()
	{
		/*
		return array(
			'dateTimeObj' => new Twig_Filter_Method($this, 'dateTimeObj')
		);
		*/

		return array();
	}

	/*
	public function dateTimeObj($dateString)
	{
		if(!$dateString instanceof DateTime)
		{
			return new DateTime($dateString);
		}

		return $dateString;
	}
	*/

}