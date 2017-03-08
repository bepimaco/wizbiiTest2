<?php

namespace WB\AnalyticsBundle\Document;

use WB\AnalyticsBundle\Services\Stat\AnalyticsException;
//use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class WBLine
{
	
	const QT_MAX = 3600;
	
    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\Field(type="int")
     */
    protected $timestamp;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $uniquetemporalhash;

    /**
     * @MongoDB\Field(type="collection")
     */
    protected $analytics;
	
	private $analyticsValues;
	private $analyticsError;
	
// Set properties, with boolean true for mandatory ones
	private $analyticsParameters = array
	(
		'v' => true,
		't' => true,
		'dl' => false,
		'dr' => false,
		'wct' => false,
		'wui' => false,
		'wuui' => false,
		'ec' => false,
		'ea' => false,
		'el' => false,
		'ev' => false,
		'tid' => true,
		'ds' => true,
		'cn' => false,
		'cs' => false,
		'cm' => false,
		'ck' => false,
		'cc' => false,
		'sn' => false,
		'an' => false,
		'av' => false,
		'qt' => false,
		'z' => false
	);
	


	/**
	* @return int
	*/
   public function getId()
   {
		return $this->id;
   }

   /**
	* @param int $title
	*/
   public function setTimestamp($timestamp)
   {
		$this->timestamp = $timestamp;
   }

   /**
	* @return int
	*/
   public function getTimestamp()
   {
		return $this->timestamp;
   }

   /**
	* @param string $uniquetemporalhash
	*/
   public function setUniquetemporalhash($uniquetemporalhash)
   {
		$this->uniquetemporalhash = $uniquetemporalhash;
   }

   /**
	* @return string
	*/
   public function getUniquetemporalhash()
   {
		return $this->uniquetemporalhash;
   }

   /**
	* @param collection $analytics
	*/
   public function setAnalytics($analytics)
   {
		$this->analytics = $analytics;
   }

   /**
	* @return collection
	*/
   public function getAnalytics()
   {
		return $this->analytics;
   }

   /**
	* @return int
	*/
   public function getWizBeeNaute()
   {
		if (isset($this->analyticsValues['wui'])) return $this->analyticsValues['wui'];
		return 0;
   }


/**
 * Apply rules defined in WizBii test
 * 
 * @access public
 * @name applyRules
 * @return void
 */
/*----------------------------------------------------------------------------*/
    public function hydrate()
	{
		
	// Define current timestamp
		$newDate = new \DateTime();
		$this->timestamp = $newDate->getTimestamp();
		
	// Convert analytics tab to JSon	
		$this->analytics = json_encode($this->analyticsValues);
		
	// Build unique hash
		$this->uniquetemporalhash = md5($this->timestamp.$this->analytics);
		
	}


/**
 * Extract parameters from $_REQUEST
 * 
 * @access public
 * @name extractParameters
 * @return void
 */
/*----------------------------------------------------------------------------*/
	public function extractParameters()
	{
		
	// Initialize
		$this->analyticsValues = [];
		$this->analyticsError = [];
		
	// Browse each value
		foreach($this->analyticsParameters as $key=>$element)
		{
			
		// Check GET or POST
			$p = filter_input(INPUT_GET, $key, FILTER_SANITIZE_STRING);
			if ($p === NULL) $p = filter_input(INPUT_POST, $key, FILTER_SANITIZE_STRING);
			
		// Add value 
			if ($p !== NULL)
				$this->analyticsValues[$key] = $p;
			
		// Check mandatory values
			if ( ($p === NULL) && ($element === true) )
				$this->addAnalyticError($key, 'missing');
			
		}
		
    }


/**
 * Apply rules defined in WizBii test
 * 
 * @access public
 * @name applyRules
 * @return void
 */
/*----------------------------------------------------------------------------*/
    public function applyRules()
    {
		
	// Rules depending on medium used
		if (isset($this->analyticsValues['cm']))
		{
			
		// Rules for mobiles
			if ($this->analyticsValues['cm'] == 'mobile')
			{

			// Wizbii creator type must be define
				if ( (!isset($this->analyticsValues['wct'])) || (empty($this->analyticsValues['wct'])) )
					$this->addAnalyticError('wct', 'missing');
				
			// Wizbii user ID must be define
				if ( (!isset($this->analyticsValues['wui'])) || (empty($this->analyticsValues['wui'])) )
					$this->addAnalyticError('wui', 'missing');

			// Application name type must be define
				if ( (!isset($this->analyticsValues['an'])) || (empty($this->analyticsValues['an'])) )
					$this->addAnalyticError('an', 'missing');
				
			}
			
		// Rules for web
			elseif ($this->analyticsValues['cm'] == 'web')
			{

			// Wizbii creator type must be define
				if ( (!isset($this->analyticsValues['wuui'])) || (empty($this->analyticsValues['wuui'])) )
					$this->addAnalyticError('wuui', 'missing');
				
			}
			
		// Rules for other media (if exists ...)
			else
			{

			// Application name type must be define
				if ( (!isset($this->analyticsValues['an'])) || (empty($this->analyticsValues['an'])) )
					$this->addAnalyticError('an', 'missing');
				
			}
		}
		
	// Rules depending on event hited
		if (isset($this->analyticsValues['t']))
		{
			
		// Rules for events
			if ($this->analyticsValues['t'] == 'event')
			{

			// Event category must be define
				if ( (!isset($this->analyticsValues['ec'])) || (empty($this->analyticsValues['ec'])) )
					$this->addAnalyticError('ec', 'missing');
				
			// Event action must be define
				if ( (!isset($this->analyticsValues['ea'])) || (empty($this->analyticsValues['ea'])) )
					$this->addAnalyticError('ea', 'missing');
				
			}
			
		// Rules for screenview
			elseif ($this->analyticsValues['t'] == 'screenview')
			{

			// Screen name type must be define
				if ( (!isset($this->analyticsValues['sn'])) || (empty($this->analyticsValues['sn'])) )
					$this->addAnalyticError('sn', 'missing');
				
			}
			
		}
		
	// If queue time is defined, it must stay beyhond value set in config file
		if ( isset($this->analyticsValues['qt']) || (!empty($this->analyticsValues['qt'])) )
		{
			if (intval($this->analyticsValues['qt']) > self::QT_MAX )
				$this->addAnalyticError('qt', 'queue_time_override', self::QT_MAX);
		}
		
	// Only version 1 is supported
		if ( isset($this->analyticsValues['v']) || (!empty($this->analyticsValues['v'])) )
		{
			if (intval($this->analyticsValues['v']) != 1 )
				$this->addAnalyticError('v', 'version_not_supported');
		}
		
	//
		if (!empty($this->analyticsError))
		{
			throw new AnalyticsException("Il y a ".count($this->analyticsError)." erreurs.", 500, null, $this->analyticsError);
		}
	
    }
	

/**
 * Register an error on analytics parameters
 * 
 * @access public
 * @name addAnalyticError
 * @return void
 */
/*----------------------------------------------------------------------------*/
    private function addAnalyticError($parameterName, $errorType, $errorDetails='')
    {
		
	// Build standard error object
		$error = array
		(
			'error' => $errorType,
			'parameter' => $parameterName,
		);
		
	// Add details if exists
		if ($errorDetails != '')
			$error['details'] = $errorDetails;
		
	// Add error to object properties
		$this->analyticsError[] = $error;
		
    }
}