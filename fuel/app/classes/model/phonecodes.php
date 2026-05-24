<?php
/*
 * 	Phonecodes
 * 
 *  Manipulates the phonecodes database
 * 
 */
namespace Model;

use \DB;
use \Utils;

class Phonecodes extends \Model 
{
    
    // Class DB object
	private $db;
	
    // Delimiters for start and end of search term
    private $delimiters = [
		's' => '',
		'e' => '',
	];
	
    // Original NI codes that were 0+4 digit format
	// Codes with different values to their keys are codes which were changed after
	// their initial allocation (i.e. breaking away from 0238x or 0762x codes)
	private $original5DigitNICodes = [
		'02477' => '02477',
		'02477' => '02477',
		'02657' => '02657',
		'02665' => '02665',
		'02667' => '02667',
		'03655' => '03655',
		'03656' => '03656',
		'03657' => '03657',
		'03967' => '03967',
		'05047' => '05047',
		'06487' => '06487',
		'06625' => '06625',
		'06626' => '06626',
		'06627' => '06627',
		'06937' => '06937',
		'08462' => '02382',
		'0960' => '02383',
		'0849' => '02384',
		'08466' => '02386',
		'0861' => '07625',
		'0820' => '07626',
		'0868' => '07627',
	];


	// Original GB codes that were 0+4 digits
	// Key is the original code they were moved from
	private $original5DigitGBCodes = [ 
		'0541' => '03873',
		'0468' => '05242',
		'0966' => '05394',
		'0448' => '05395',
		'0587' => '05396',
		'0965' => '06973',
		'0699' => '06974',
		'0697' => '06977',
		'0930' => '07683',
		'0853' => '07684',
		'0596' => '07687',
		'0940' => '09467',
	];

	// 00xx code mappings
	// Value is the code they were moved to
	private $mapping00xxCodes =  [
		'0022' => '0301',
		'0023' => '0572',
		'0024' => '0631',
		'0025' => '0866',
		'0027' => '0852',
		'0040' => '0850',
		'0042' => '0656',
		'0044' => '0851',
		'0046' => '0859',
		'0047' => '0871',
		'0052' => '0837',
		'0055' => '0651',
		'0062' => '0855',
		'0063' => '0662',
		'0072' => '0689',
		'0073' => '0691',
		'0074' => '0695',
		'0076' => '0856',
		'0077' => '0857',
		'0082' => '0832',
		'0083' => '0959',
		'0085' => '0801',
		'0086' => '0830',
		'0092' => '0865',
		'0093' => '0883',
		'0095' => '0869',
		'0096' => '0867',
	];

    /**
     * Constructor
     * 
     */
	public function __construct()
	{
        // Assign class DB object
		$this->db = DB::instance('phonecodes');
		
        // Set the correct search delimiters for the environment
		$this->delimiters = $this->getSearchDelimiters();
	}
	
    
    /**
     *  @function getCodeInfo
     *  @descripton get information about the requested area code
     * 
     *  @access public
     *  @param string $search the search term - can either be a numeric area code or a code name
     *  @return array matching search information
     */
	public function getCodeInfo($search)
	{
		$return = [];
		
        // Assume we're not searching for a range
        $range = false;
        
        // If the search term looks like this: 028-90
        // the number after the hyphen signifies the range to return
        // so we get that into a variable here and use the first part as the search term
        if (stripos($search, '-') !== false) {
            $tmpSearch = explode('-', $search);

            $search = $tmpSearch[0];
            $range = $tmpSearch[1];
        }

        // Search against the STD code (numeric), 
        // or the code name - in this case, we use the search delimiters to isolate the search term
		$codes = $this->db->select(['`ExchangeGroupList`.`STDCode`', '`Range`', '`Name`', 
									'`GroupType`', '`GroupID`', '`GroupName`', 
									'`PreviousCodes`', '`OriginalCode`', '`mapping`', '`mappingReason`', '`otherNotes`'])
						  ->from('ExchangeGroupList')
						  ->join('ChargeGroupExchanges', 'LEFT')
						  ->on('`ChargeGroupExchanges`.`ExchangeGroupID`', '=', '`ExchangeGroupList`.`id`')
						  ->where('`ExchangeGroupList`.`STDCode`', 'like', $search)
						  ->or_where('`ExchangeGroupList`.`Name`', 'REGEXP', $this->delimiters['s'] . $search . $this->delimiters['e'])
						  ->execute();

        // Get all matching exchanges for all codes
		$return = $this->getExchangesForCodes($codes, $range);

        // Return the data
		return $return;
	}
	
    
    /**
     *  @function getExchangeInfo
     *  @descripton get information about the requested exchange
     * 
     *  @access public
     *  @param string $search the search term (exchange name or MDF reference)
     *  @return array matching search information
     */
	public function getExchangeInfo($search)
	{
	
		$return = [];
        
        // Search against the current exchange name, the alternative exchange name, or the MDF ID
        // In the case of the first two, we again use the search delimeters to isolate the search term
		$exchanges = $this->db->select(['*'])
							  ->from('ExchangeList')
							  ->where('ExchangeName', 'REGEXP', $this->delimiters['s']  . $search . $this->delimiters['e'] )
							  ->or_where('AltName', 'REGEXP', $this->delimiters['s']  . $search . $this->delimiters['e'] )
							  ->or_where('MDFReference', '=', strtoupper($search)) 
							  ->execute();
		
        // Iterate over the returned results
        foreach ($exchanges as $exchg) {
            
            // Explode network info into an array
			$networkInfo = explode('|', $exchg['NetworkZoneAndDistrict']);

            // Create temoprary array of data
			$tmp = [
				'ID' => $exchg['MDFReference'],
				'Name' => $exchg['ExchangeName'],
				'AltName' => $exchg['AltName'],
				'NetworkInfo' => [
					'Zone' => $networkInfo[0],
					'District' => substr($networkInfo[1], 0, -5),
				],
				'Postcode' => $exchg['Postcode'],
				'MapLink' => \Utils::createMapLink($exchg['Postcode']),
				'STDCode' => $exchg['STDCode'],
				'Range' => $exchg['Range'],
				'Sector' => $exchg['Sector'],
				'ExchangeGroup' => $exchg['ExchangeGroup'],
				'GroupType' => $exchg['GroupType'],
				'OriginalCode' => $exchg['OriginalSTDCode'],
				'AdditionalInfo' => $this->getAdditionalInfo($exchg, 'MDFReference'),
			];
			
            // Remove any empty elements from the resultset to keep things tidy
			$return[] = $this->removeEmptyElements($tmp);

		}

        // Return the data
		return $return;
	}

    /**
     *  @function getChargeInfo
     *  @descripton get information about the requested charge group
     * 
     *  @access public
     *  @param string $search the search term (exchange name or MDF reference)
     *  @return array matching search information
     */
	public function getChargeGroupInfo($search)
	{
		$groupMap = [];

		$return = [];
        
        // Stage 1; get charge groups
		$groupExchangeGroups = $this->db->select(['`id_exchangeGroup`'])
							  ->from('mapChargeGroupsExchangeGroups')
							  ->join('ChargeGroupList', 'LEFT')
						  	  ->on('`ChargeGroupList`.`ref`', '=', '`mapChargeGroupsExchangeGroups`.`id_ChargeGroup`')
							  ->where('groupName', 'REGEXP', $this->delimiters['s']  . $search . $this->delimiters['e'] )
							  ->execute();
		foreach ($groupExchangeGroups as $code) {
			$groupMap[] = $code['id_exchangeGroup'];
		}

		// Only continue if there are results from stage one
		if (!empty($groupMap)) {
			// Search against the STD code (numeric), 
			// or the code name - in this case, we use the search delimiters to isolate the search term
			$codes = $this->db->select(['`ExchangeGroupList`.`STDCode`', '`Range`', '`Name`', 
										'`GroupType`', '`GroupID`', '`GroupName`', 
										'`PreviousCodes`', '`OriginalCode`', '`mapping`', '`mappingReason`', '`otherNotes`'])
							->from('ExchangeGroupList')
							->join('ChargeGroupExchanges', 'LEFT')
							->on('`ChargeGroupExchanges`.`ExchangeGroupID`', '=', '`ExchangeGroupList`.`id`')
							->where('`id`', 'IN', $groupMap)
							->execute();

			// Get all matching exchanges for all codes
			$return = $this->getExchangesForCodes($codes);
		}

        // Return the data
		return $return;
	}
    
    /**
     *  @function gethistoricalCodeInfo
     *  @descripton get historical information about the requested area code
     * 
     *  @access public
     *  @param string $search the search term (a historical code)
     *  @return array matching search information
     */
	public function getHistoricalCodeInfo($search)
	{
				
		$return = [
			'Codes' => [],
			'Exchanges' => []
		];

		// Firstly, we need to process the search term a bit
		$processedSearch = $this->processHistoricalSearchTerm($search);

		if ($processedSearch === false) {
			return $return;
		}

		// Stage 1: search against historical dialling codes

		$oldCodes = $this->db->select(['*'])
							  ->from('HistoricalData')
							  ->where('code', '=', $processedSearch)
							  ->order_by('code','asc')
							  ->execute();

		foreach ($oldCodes as $row) {

            $tmp = [
				'STDCode' => $row['code'], 
				'Name' => $row['name'],
				'NameClean' => $this->cleanseName($row['name']),
				'GroupType' => $this->formatGroupType($row['groupType']),
				'Mapping' => $row['mapping'],
				'MappingReason' => empty($row['mappingReason'])?$row['name']:$row['mappingReason'],
				'Routing' => $this->formatRouting($row),
				'MovedFrom' => empty($row['movedFrom']) ? "-":$row['movedFrom'],
				'MovedTo' => empty($row['movedTo']) ? "-":$row['movedTo'],
				'OtherNotes' => empty($row['otherNotes']) ? "None":$row['otherNotes'],
			];
			$return['Codes'][] = $this->removeEmptyElements($tmp);
		}

		// Extra stage here: if the processed search is 00xx code
		// look up the mapping to get the replacement for the exchange search
		if (array_key_exists($processedSearch, $this->mapping00xxCodes)) {
			$search = $this->mapping00xxCodes[$processedSearch];
		}

		// Do the same for the mixed 5+5 (non-NI) areas but use the original search
		$mapped = array_search($search, $this->original5DigitGBCodes);

		if ($mapped != false) {
			$search = $mapped;
		}

        // Stage 2: search against the original STD code in the exchange list
        // We will also do a search from the "left" of the code
        // eg if someone searches for 0970 then search on '0970' and '0970%'
        // to get the satellite exchanges in that code
		$exchanges = $this->db->select(['*'])
							  ->from('ExchangeList')
							  ->where('OriginalSTDCode', 'LIKE', $search . '%')
							  ->or_where('OriginalSTDCode', 'LIKE', '%/' . $search . '%')
							  ->order_by('OriginalSTDCode','asc')
							  ->execute();
	
        // Iterate over the returned results
		foreach ($exchanges as $exchg) {
             // Explode network info into an array
			$networkInfo = explode('|', $exchg['NetworkZoneAndDistrict']);

            // Create temoprary array of data
			$tmp = [
				'ID' => $exchg['MDFReference'],
				'Name' => $exchg['ExchangeName'],
				'AltName' => $exchg['AltName'],
				'NetworkInfo' => [
					'Zone' => $networkInfo[0],
					'District' => substr($networkInfo[1], 0, -5),
				],
				'Postcode' => $exchg['Postcode'],
				'MapLink' => \Utils::createMapLink($exchg['Postcode']),
				'STDCode' => $exchg['STDCode'],
				'Range' => $exchg['Range'],
				'Sector' => $exchg['Sector'],
				'ExchangeGroup' => $exchg['ExchangeGroup'],
				'GroupType' => $exchg['GroupType'],
				'OriginalCode' => $exchg['OriginalSTDCode'],
				'AdditionalInfo' => $this->getAdditionalInfo($exchg, 'MDFReference'),
			];

            // Remove any empty elements from the resultset to keep things tidy
			$return['Exchanges'][] = $this->removeEmptyElements($tmp);
		}

        // Return the data
		return $return;
	}
	

    /**
     *  @function gethistoricalCodeInfo
     *  @descripton get historical code information about the requested name
     * 
     *  @access public
     *  @param string $search the search term (a historical code)
     *  @return array matching search information
     */
	public function getHistoricalNameInfo($search)
	{
				
		$return = [
			'Codes' => [],
			'Exchanges' => []
		];

		// Firstly, we need to process the search term a bit
		if (strlen($search) < 4) {
			return $return;
		}

		// Stage 1: search against historical dialling codes
		$oldCodes = $this->db->select(['*'])
							  ->from('HistoricalData')
							  ->where('name', 'LIKE', '%' . $search . '%')
							  ->order_by('code','asc')
							  ->execute();

		foreach ($oldCodes as $row) {

            $tmp = [
				'STDCode' => $row['code'], 
				'Name' => $row['name'],
				'NameClean' => $this->cleanseName($row['name']),
				'GroupType' => $this->formatGroupType($row['groupType']),
				'Mapping' => $row['mapping'],
				'MappingReason' => empty($row['mappingReason'])?$row['name']:$row['mappingReason'],
				'Routing' => $this->formatRouting($row),
				'MovedFrom' => empty($row['movedFrom']) ? "-":$row['movedFrom'],
				'MovedTo' => empty($row['movedTo']) ? "-":$row['movedTo'],
				'OtherNotes' => empty($row['otherNotes']) ? "None":$row['otherNotes'],
			];
			$return['Codes'][] = $this->removeEmptyElements($tmp);
		}

        // Return the data
		return $return;
	}

	/**
	 * @function formatGroupType
	 * @description formats the supplied group type letter into a full string
	 * 
	 * @access private
	 * @param string $groupType the group type letter
	 * 
	 * @return string the full group name
	 */
	private function formatGroupType($groupType)
	{
		switch ($groupType) {
			case "D" :
				return "Director";
				break;

			case "C" :
				return "Core";
				break;

			case "R" :
				return "Ring";
				break;
		}

		// Assume anything else is "standard"
		return "Standard";
	}

	/**
	 * @function formatRouting
	 * @description formats the routing information for the supplied code
	 * 
	 * @access private
	 * @param array $row the row from the data object containing the routing information
	 * 
	 * @return string the formatted routing string
	 */
	private function formatRouting($row)
	{
		// If no routing, return a dash
		if (empty($row['routingParent'])) {
			return "-";
		}

		// Routing format example: 0772 + 8
		return "{$row['routingParent']} + {$row['routingLevel']}";
	}

	/**
	 * @function getExchangesForCodes
	 * @description For the supplied code (and range), get all associated exchanges
	 * 
	 * @access private
	 * 
	 * @param array $codes the codes to retrieve exchanges for
	 * @param mixed $range if supplied, searches on a subrange in that code (for 02x or ELNS codes). Defaults to false
	 * 
	 * @return array matchin exchange information
	 */
	private function getExchangesForCodes($codes, $range = false)
	{
		$return = [];

		foreach ($codes as $row) {
            
            // If we have a range to search...
            if ($range !== false) {
                
                // Get all the number ranges for this code
                $ranges = explode(',', $row['Range']);

                // Ignore if none of the code's ranges match the search range
                if (in_array($range, $ranges) === false) {
                    continue;
                }
            }
            
            // For each code, get the exchanges associated with the code
			$exchanges['main'] = $this->db->select(['*'])
								  ->from('ExchangeList')
							      ->where('STDCode', 'like', $row['STDCode'])
								  ->execute();
            
			// Add extra information for director information
			if ($row['GroupType'] ===  'Director') {
				$additional = $this->db->select(['*'])
								  ->from('DirectorAreaMappings')
							      ->where('STDCode', 'like', $row['STDCode'])
								  ->execute();

				foreach ($additional as $addtl) {
					$exchanges['additional'][$addtl['id']] = $addtl;
				}
			}

            // Format the list of exchanges
            $exchangeList = $this->formatExchangeList($exchanges, $row['Name'], $row['GroupType']);
            
			// For codes with no mapping, replace with hyphen
			if (empty($row['mapping']) || $row['mapping'] === '0xxx') {
				$row['mapping'] = '-';
			}

			// Previous and original codes need further fettling
			list($prev, $orig) = $this->formatPrevOrig($row['PreviousCodes'], $row['OriginalCode']);

            // Create a temporary array of data
            $tmp = [
				'STDCode' => $row['STDCode'], 
				'NumberRange' => $row['Range'], 
				'Name' => $row['Name'],
				'NameClean' => $this->cleanseName($row['Name']),
				'GroupType' => $row['GroupType'], 
				'ChargeGroup' => [
					'Name' => $row['GroupName'],
					'ID' => $row['GroupID'],
				],
				'PreviousCodes' => ($row['STDCode'] === '01987' ? "-" : $prev),
				'OriginalCode' => ($row['STDCode'] === '01987' ? "-" : $orig),
				'Mapping' => $row['mapping'],
				'MappingReason' => $row['mapping'] === "-" ? "N/A" : (empty($row['mappingReason']) ? $row['Name'] : $row['mappingReason']),
				'OtherMappingNotes' => empty($row['otherNotes']) ? "None":$row['otherNotes'],
				'Exchanges' => [
                    'Count' => count($exchangeList['data']),                
                    'List' => $exchangeList['data'],
					'Fields' => $exchangeList['fields'],
                ],
			];
			
            // Remove any empty elements from the resultset to keep things tidy
			$return[] = $this->removeEmptyElements($tmp);
		}

		return $return;
	}



    /**
     *  @function getAdditionalInfo
     *  @descripton get additional informaiton about this exchange
     * 
     *  @access private
     *  @param array $exchg the exchange
	 *  @param string $source which field to search in the database
     *  @return mixed matching info, else empty string
     */
	private function getAdditionalInfo($exchg, $source)
	{
		// $source will either be MDFReference or STDCode
		if ($exchg['GroupType'] === 'Director') {
			$additional = $this->db->select(['*'])
					->from('DirectorAreaMappings')
					->where($source, 'LIKE', $exchg[$source])
					->execute();

			// Iterate over the results
			if (count($additional) > 0) {
				
				// Build up temporay array of results
				$tmp = [];
				foreach ($additional as $addtl) {
					$tmp[$addtl['id']] = $addtl;
				}
				
				// If only one result returned, return the "flattened" result
				if (count($tmp) === 1) {
					$rtn = array_shift($tmp);
					return $rtn;
				}

				// Return temporary result array
				return $tmp;
			}
			
			// No results found, return dummy data for the moment
			return [
				'preAFNCode' => "-",
				'postAFNCode' => "-",
				'afnRoutingSector' => "-",
				'notes' => "-",
			];
		}

		// No results, return blank
		return '';
	}

    /**
     *  @function formatExchangeList
     *  @descripton format the list of exchanges into an array structure
     * 
     *  @access private
     *  @param array $exchangeList the list of exchanges
     *  @param string $groupName the exchange group name
     *  @param string $groupType the exchange group type
     *  @return array matching search information
     */
	private function formatExchangeList($exchangeList, $groupName, $groupType)
	{

		// Data to be returned
		$return = [];
		
		$fields = [];

        // Iterate over the list of exchanges
		foreach ($exchangeList['main'] as $exchg) {
            
            // Ignore any exchanges where thegroup name is not the same as the supplied group name
            // (this is mainly for mixed or ELNS areas)
			if (strtolower($exchg['ExchangeGroup']) != strtolower($groupName)) {
				continue;
			}
            
            // Explode network info into an array
			$networkInfo = explode('|', $exchg['NetworkZoneAndDistrict']);
			
            // Create temporary array of data
			$tmp = [
				'ID' => $exchg['MDFReference'],
				'Name' => $exchg['ExchangeName'],
				'AltName' => $exchg['AltName'],
				'NetworkInfo' => [
					'Zone' => $networkInfo[0],
					'District' => substr($networkInfo[1], 0, -5),
				],
				'Postcode' => $exchg['Postcode'],
                'MapLink' => \Utils::createMapLink($exchg['Postcode']),
                // Format the sector appropriately
				'Sector' => $this->formatSector($exchg['Sector'], $groupType),
				'OriginalCode' => $exchg['OriginalSTDCode'],
			];

			if (array_key_exists('additional', $exchangeList)) {
				$tmp['AdditionalInfo'] = [
					'preAFNCode' => $exchangeList['additional'][$exchg['id']]['preAFNCode'],
					'postAFNCode' => $exchangeList['additional'][$exchg['id']]['postAFNCode'],
					'afnRoutingSector' => $this->formatRoutingSector($exchangeList['additional'][$exchg['id']]['afnRoutingSector']),
					'notes' => $exchangeList['additional'][$exchg['id']]['notes'],
				];
			}
			
            // Remove any empty elements from the resultset to keep things tidy
			$return[] = $this->removeEmptyElements($tmp);
			
			$fields = array_keys($return[0]);
		}
		
        // Return the data
		return ['data' => $return, 'fields' => $fields];
	}

	/**
	 * @function formatRoutingSector
	 * @description format the routing sector identifier into a meaningful name
	 * 
	 * @access private
	 * @param string $sector the sector identifier
	 * @return string the meaningful name
	 */
	private function formatRoutingSector($sector)
	{
		if (empty($ector)) {
			return null;
		}

		$routingMap = [
			"C" => "Central",
			"E" => "East",
			"S" => "South",
			"SE" => "South East",
			"SW" => "South West",
			"N" => "North",
			"NW" => "North West",
			"W" => "West",
		];

		if (array_key_exists(strtoupper($sector), $routingMap)) {
			return $routingMap[$sector];
		}

		return null;
	}
	
    /**
     *  @function formatPrevOrig
     *  @descripton format previous/original code information
     * 
     *  @access private
     *  @param mixed $prev previous code information
     *  @param string $orig original code information
     *  @return array the formatted code information
     */
	private function formatPrevOrig($prev, $orig)
	{
		// Create array from previous codes
		$tempprev = explode("|", $prev);

		// Orig may not be returned by the DB, use first element of previous array
		if(empty($orig)) {
			$orig = $tempprev[0];
		}
		
		// Bit of hard coding for London, not great but needs must
		if ($orig === "071/0181") {
			$orig = "01/071/081";
		}

		// Return last element from temppprev as previous and formatted orig as original
		return [
			array_pop($tempprev),
			$orig,
		];
	}
    
    /**
     *  @function formatSector
     *  @description Returns sector information if appropriate
     * 
     * @access private
     * 
     * @param string $sector the sector
     * @param string $groupType the group type
     * @return array the sector if appropriate, else an empty string
     */
	private function formatSector($sector, $groupType) 
	{
        // Return the sector if the group type is either director or ELNS
        // Return a blank string in all other cases
		if (strtolower($groupType) === 'director' || strtolower($groupType) === 'elns') {
			return $sector;
		}
		return '';
	}
	
	/**
     *  @function cleanseName
     *  @description Cleanse non-alpha characters
     * 
     * @access private
     * 
     * @param string $name
     * @return string the cleansed name
     */
	private function cleanseName($name)
	{
		$name = str_replace(["(",")"], "", $name);
		$name = str_replace([" ","/"], "-", $name);
		return $name;
	}
    
    /**
     *  @function getSearchDelimiters
     *  @description returns the correct search delimiters for the environment
     * 
     * @access private
     * 
     * @return array of appropriate delimiters
     */
	private function getSearchDelimiters()
	{
        /* At the time of writing (April 2024), the hosting I use is running an older version of MySQL
         * which does not recognise the newer "\\b" delimiters for making word boundaries when using REGEXP
         * 
         * Luckily, FuelPHP allows us to label the environment so we can do a check here to see where the code is running
         * and return appropriate delimiters
         */
		if (\Fuel::$env === 'production') {
			return [
				's' => '[[:<:]]',
				'e' => '[[:>:]]',
			];
		}
		
		return [
			's' => '\\b',
			'e' => '\\b',
		];
	}

	/**
	 * @function processHistoricalSearchTerm
	 * @description Processes the supplied search term if its numeric so that expected information
	 * 				is returned
	 * 
	 * @access private
	 * 
	 * @param string $search the search term to process
	 * 
	 * @return mixed the processed search if successful, else false
	 */
	private function processHistoricalSearchTerm($search)
	{
		// All processing is determined on the length of the string
		switch (strlen($search)) {
			
			case 6 :
				// Simply return the first 4 characters
				return (substr($search, 0, 4));
				break;


			case 5:
				// Stage 1; check to see if the code was an original NI code
				$mappedSearch = array_search($search, $this->original5DigitNICodes);
				
				// Mapping found?
				if ($mappedSearch !== false) {
					// If the mapped search does not match the orginal search, return mapping,
					// else return first 4 characters of search
					return ($mappedSearch === $search ? $search : substr($mappedSearch, 0, 4));
				}

				// Stage 2; check to see if the code was an original 5 digit GB code
				$mappedSearch = array_search($search, $this->original5DigitGBCodes);

				// Mapping found?
				if ($mappedSearch !== false) {
					// Return mapping
					return $mappedSearch;
				}

				// Mapping not found, return first 4 characters of search
				return (substr($search, 0, 4));
				break;		 

			case 4:
				// Simply return searcxh as is
				return $search;
				break;
			
			case 3 :
				// Return search if the last character is 1 (e.g. 051 or 031)
				if (substr($search, -1) === '1') {
					return $search;
					break;
				}
				break;

			case 2 :
				// Return search only if it matches 01 for London
				if ($search === '01') {
					return $search;
					break;
				}
				break;
		}
		
		// Return false in all other cases not highlighted above
		return false;
	}

    /**
     *  @function removeEmptyElements
     *  @description recursively removes empty elements from arrays 
     * 
     * @access private
     * @param array $array the array to remove empty elements from
     * @return array the array with emprty elements removed
     */
	private function removeEmptyElements($array)
	{
        // Backwards compatiable with serveers which don't allow for null coalesce
		return array_filter($array, function($value) {
				return (!is_null($value) &&  $value !== '');
		});
	}
}
