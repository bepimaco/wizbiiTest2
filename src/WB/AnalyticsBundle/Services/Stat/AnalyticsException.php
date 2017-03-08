<?php

namespace WB\AnalyticsBundle\Services\Stat;

class AnalyticsException extends \Exception
{
	
	private $details;
	
	public function __construct($message, $code, $previous, $details)
	{
		parent::__construct($message, $code, $previous);
		$this->details = $details;
	}
	
	public function getDetails()
	{
		return $this->details;
	}
}