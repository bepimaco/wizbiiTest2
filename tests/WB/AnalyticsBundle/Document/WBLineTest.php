<?php

namespace \WB\AnalyticsBundle\Document; 
 
use WB\AnalyticsBundle\Document\WBLine;
use WB\AnalyticsBundle\Services\Stat\AnalyticsException;
 
class WBLineTest extends \PHPUnit_Framework_TestCase 
{ 

	public function testCheckQT() 
	{
	
	//
		$WBLine = new WBLine();
		$error = '';
		
	// Set values to analyze
		$analyticsValues = array
		(
			'v' => 1,
			'wuui' => 45,
			'tid' => 45,
			't' => 'pageview',
			'dl' => 'http://www.wizbii.com/bar',
			'ec' => 'ads',
			'el' => 'client',
			'ea' => 'Click Masthead',
			'ds' => 'web',
			'cn' => 'bpce',
			'wui' => 123,
			'cs' => 'wizbii',
			'cm' => 'web',
			'ck' => 'erasmus',
			'cc' => 'foobar',
			'qt' => '3700'
		);
		$WBLine->setAnalyticsValues($analyticsValues);
		
	//
		try
		{
			$wbLine->applyRules();
		}
		catch(AnalyticsException $e)
		{
			$error = json_encode($e->getDetails());
		}
 
    // assert that your calculator added the numbers correctly! 
		$this->assertContains('queue_time_override', $error);
    } 
}