<?php
/**
 * Utility class for snathenet
 */

class Utils {

    /**
     * @function createMapLink
     * @description create a clickable map link from the supplied data
     * 
     * @access public
     * @param mixed $data the data to build the link from. Can be a string (postcode) or aray (lat/long coords)
     * 
     * @return string HTML link if matching format found, else "Unavailable"
     */
    public static function createMapLink($data)
    {
        // Array of data? Assume lat/long coordinnates
        if (is_array($data)) {
            return self::createMapLinkFromLatLong($data);
        }

        // Is postcode? 
        if (self::isUKPostcode($data)) {
            return self::createMapLinkFromPostcode($data);
        }

        // No matches for data found, return "Unavailable"
        return "Unavailable";
    }

    /**
     * @function isUKPostcode
     * @description checks supplied string to see if it matches UK postcode format
     * 
     * @access public
     * @param string $query the string to check
     * 
     * @return bool whether the queried string matches UK postcode format
     */
    public static function isUKPostcode($query)
	{
		// Remove all whitespace
		$query = preg_replace('/\s/', '', $query);
	 
		// Make uppercase
		$query = strtoupper($query);
	 
		// Return outcome of match attempt
		return (preg_match("/^[A-Z]{1,2}[0-9]{2,3}[A-Z]{2}$/",$query)
			|| preg_match("/^[A-Z]{1,2}[0-9]{1}[A-Z]{1}[0-9]{1}[A-Z]{2}$/",$query)
			|| preg_match("/^GIR0[A-Z]{2}$/", $query));
	}

    /**
     * @function createMapLinkFromLatLong
     * @description create a clickable map using the supplied array of lat/long coords
     * 
     * @access private
     * @param array $coords the coordinates to build the link from. Assume lat is first
     * 
     * @return string HTML link
     */
    private static function createMapLinkFromLatLong($coords)
    {	
        // Use the native FuelPHP function to create the link
        return \Html::anchor(
            str_replace(["*LAT*", "*LONG*"], [$coords[0], $coords[1]], getenv('BASE_MAPLINK_URL') . "?mlat=*LAT*&mlon=*LONG*#map=13/*LAT*/*LONG*"), 
            "{$coords[0]}, {$coords[1]}", 
            ['target' => '_blank']
        );
    }

    /**
     * @function createMapLinkFromPostcode
     * @description create a clickable map using the supplied postcode string
     * 
     * @access private
     * @param string $postcode the supplied postcode
     * 
     * @return string HTML link
     */
    private static function createMapLinkFromPostcode($postcode)
    {
		// Use the native FuelPHP function to create the link
		return \Html::anchor(
            str_replace("*POSTCODE*", $postcode, getenv('BASE_MAPLINK_URL') . "search?query=*POSTCODE*"), 
            $postcode, 
            ['target' => '_blank']
        );
    }
}