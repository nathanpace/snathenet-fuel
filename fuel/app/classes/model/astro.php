<?php
/*
 * Astro
 * 
 * Model for "astronomical" information
 * 
 * Returns sunrise/sunset/local time info
 */
namespace Model;

class Astro extends \Model 
{
	private $lat;	// latitude
	private $long;	// longitude
	
	private $timezone; // timezone reference
	
	private $sunInfo;			// sunrise/sunset info
	private $timeOffsetInfo;	// local time offset info
	
	/**
	 * Constructor
	 *
	 * Populates object values
	 *  
	 * @param float $lat latitiude value
	 * @param float $long longitude value
	 */ 
	public function __construct($lat, $long)
	{
		$this->lat = $lat;
		$this->long = $long;
		
		// Ensure all times relate to UK time (not just UTC)
		$this->timezone = getenv('HOME_TZ');
		
		// Calculate sunrise/sunset info and time offset info
		$this->sunInfo = $this->initialiseSunInfo();
		$this->timeOffsetInfo = $this->calculateTimeOffset();
	}
	
	/**
	 *	@function initialiseSunInfo()
	 * 	
	 *  @description initialise sunrise/sunset info 
	 * 
	 *  @access private
	 * 
	 *  @return array sunrise/sunset info for today and tomorrow
	 */
	private function initialiseSunInfo() 
	{
		
		// Get object timezone
		$timezone = new \DateTimeZone($this->timezone);
		
		// Create date/time object for today with home timezone
		$today = new \DateTime('today');
		$today->setTimeZone($timezone);
		
		// Create another date/time object for tomorrow, again with home timezone
		$tomorrow = new \DateTime('tomorrow');
		$tomorrow->setTimeZone($timezone);

        // Get sunrise and sunset information for today and tomorrow, passing in the newly created date objects
        $sunToday = $this->calculateAndConvertSunInfo($today);
        $sunTomrw = $this->calculateAndConvertSunInfo($tomorrow);
        
        // Work out the difference in sunrise/sunset times
        $sunTomrw['sunriseDiff'] = $this->calculateDiff($sunTomrw['sunrise_secs'], $sunToday['sunrise_secs']);
        $sunTomrw['sunsetDiff'] = $this->calculateDiff($sunTomrw['sunset_secs'], $sunToday['sunset_secs']);
        
        // Remove the elements from the array that we no longer need
        unset($sunToday['sunrise_secs'], $sunToday['sunset_secs'], $sunTomrw['sunrise_secs'], $sunTomrw['sunset_secs']);
    
		// Return calculated sunrise/sunset info for today and tomorrow
		return [
			'today' => $sunToday,
			'tomorrow' => $sunTomrw,
		];
	}
	
    
    /**
	 *	@function calculateDiff()
	 * 	
	 *  @description Calculate difference in sunrise/sunset times between tomorrow and today
	 * 
	 *  @access private
	 * 
	 *  @return string formated string
	 */
    private function calculateDiff($tomrw, $today)
    {
        // Get difference in seconds
        $diff = $tomrw - $today;
    
        // If it's a negative difference, consider it as "earlier"
        $direction = ($diff < 0 ? 'earlier' : 'later');
        
        // Get absolute number of seconds in difference (i.e. without plus/minus sign)
        $diff = abs($diff);
        
        // Get minutes and seconds from total number of seconds
        $min = intval($diff / 60);
        $secs = $diff % 60;
        
        // Format string and return
        return "{$min}m {$secs}s {$direction}";

    }
    
    
	/**
	 * 	@function calculateAndConvertSunInfo()
	 * 
	 * 	@description calls date_sun_info for the supplied date, lat and long,
	 *  			 and returns information with timestamps converted to H:m:s
	 * 
	 * 	@access private
	 * 
	 * 	@param \DateTime Datetimeobject
	 * 
	 *  @return array of converted info from call
	 * 
	 */
	private function calculateAndConvertSunInfo($date)
	{
		// Create info array, add formatted date from supplied object
		$info = [
			'date' => $date->format('D, M d Y'),
		];
		
		// Call date_sun_info, passing in timestamp from supplied date object, lat and long,
		$dsi = date_sun_info($date->getTimestamp(), $this->lat, $this->long);

		// Iterate over returned array values
		foreach ($dsi as $key => $ts) {
			// Ignore anything that's not sunrise or sunset information at this stage
			if ($key !== 'sunrise' && $key !== 'sunset') {
                continue;
            }
            
			// Create DateTime object from the timestamp
			$dtObj = new \DateTime('@' . $ts);
			
			// Set timezone to that in the supplied date/time object
			$dtObj->setTimezone($date->getTimezone());
			
			// Add timestamp formatted as H:i.s to info array, keyed on key from original array
			$info[$key] = $dtObj->format('H:i.s');
            
            // Extra temp field which returns in H:i:s
            $info[$key . "tmp"] = $dtObj->format('H:i:s');
		}
		
        // Reference point for midnight,measured in seconds since Unix Epoch
        $midnight = strtotime("00:00");
        
        // Convert temp times to seconds
        $sr = strtotime($info['sunrisetmp']);
        $ss = strtotime($info['sunsettmp']);

        // Work out seconds since midnight
        $sunrise_secs = $sr - $midnight;
        $sunset_secs = $ss - $midnight;
        
        // Add seconds since midnight to the array
        $info['sunrise_secs'] = $sunrise_secs;
        $info['sunset_secs'] = $sunset_secs;
		
        // Remove temporary data
        unset($info['sunrisetmp'], $info['sunsettmp']);
        
        // Return complete formatted info array
		return $info;
	}

	/**
	 *	@function calculateTimeOffset()
	 * 
	 *  @description Calculates local time offset for the supplied longitude
	 * 
	 *  @access private 
	 * 
	 * 	@return array of time offset information
	 * 
	 */
	private function calculateTimeOffset() 
	{
		
		// Normalise longitude
		if ($this->long > -0.0001 && $this->long < 0.0001) {
			$this->long = 0;
		}

		// Work out the number of minutes difference the longitude value refers to
		// 1 degree = 4 minutes
		$minsDifference = 4 * $this->long;
		
		// Set flag for whether this is a negative difference
		$isNegative = ($minsDifference < 0 ? TRUE : FALSE);

		// Get the absolute value of the minute difference
		$minsDifference = abs($minsDifference);

		// Split the minute difference into separate minutes and seconds values
		$difference = array_combine (['mins','secs'], explode(".", $minsDifference));

		// Set secs value to zero if empty
		if (empty($difference['secs'])) {
			$difference['secs'] = "00000";
		}

		// Convert seconds value to a fraction of a single minute by adding "0." to the beginning
		$minfrac =  "0." . $difference['secs'];
		
		// Convert this fraction to whole seconds
		$secs = round($minfrac * 60);
		
		// Convert the minutes value to seconds
		$minsecs = $difference['mins'] * 60;
		
		// Add the two lots of seconds to get total seconds for the minute difference
		$totalSecs = $minsecs + $secs;

		// Work out decimal minute value of the minute difference
		$totalMins = round($totalSecs/60, 3);
		
		// Determine direction of difference
		$direction = ($isNegative ? "behind" : "ahead");
		
		// Determine symbol
		$symbol = ($isNegative ? "-" : "+");
		
		// Return information
		return [
		  'total_mins' => $symbol . $totalMins,
          'total_secs' => $symbol . $totalSecs,
          'diff' => sprintf('%02d', $difference['mins']) . " mins " . sprintf('%02d', $secs)  . " secs",
          'direction' => $direction,
        ];

	}

	/**
	 * @function getSunInfo
	 * @description gets the sun info stored in object
	 * 
	 * @access public
	 * 
	 * @return array of sun info
	 */
	public function getSunInfo()
	{
		return $this->sunInfo;
	}
	
	/**
	 * @function getTimeOffsetInfo
	 * @description gets the time offset info stored in object
	 * 
	 * @access public
	 * 
	 * @return array of sun info
	 */
	public function getTimeOffsetInfo()
	{
		return $this->timeOffsetInfo;
	}

	/**
	 * @function getLatLong
	 * @description returns lat/long info from object as an associative array
	 * 
	 * @access public
	 * @return array of lat/long data
	 */
	public function getLatLong()
	{
		return [
			'lat' => $this->lat,
			'long' => $this->long,
		];
	}
	
	/**
	 * @function getTimezone
	 * @description returns timezone stored in object
	 * 
	 * @access public
	 * @return string timezone
	 */
	public function getTimezone()
	{
		return $this->timezone;
	}
}
