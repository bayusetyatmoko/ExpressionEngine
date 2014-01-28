<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ExpressionEngine - by EllisLab
 *
 * @package		ExpressionEngine
 * @author		EllisLab Dev Team
 * @copyright	Copyright (c) 2003 - 2013, EllisLab, Inc.
 * @license		http://ellislab.com/expressionengine/user-guide/license.html
 * @link		http://ellislab.com
 * @since		Version 2.8
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * ExpressionEngine Relative_Date Class
 *
 * @package		ExpressionEngine
 * @subpackage	Core
 * @category	Core
 * @author		EllisLab Dev Team
 * @link		http://ellislab.com
 */

class Relative_date {

	public function create($timestamp, $reference = NULL)
	{
		return new Relative_Date_object($timestamp, $reference);
	}

}

/**
 * Relative date object created for each instance of pagination.
 */
class Relative_date_object {
	public $singular 			= 'one';
	public $less_than 			= 'less than';
	public $about				= 'about';
	public $past 				= '%s ago';
	public $future 				= 'in %s';

	private $_units				= array();
	private $_deltas			= array();
	private $_calculated_units	= array();
	private $_timestamp			= 0;
	private $_reference			= 0;
	private $_valid_units		= array('years', 'months', 'weeks', 'days', 'hours', 'minutes', 'seconds');

	public function __construct($timestamp, $reference = NULL)
	{
		$this->_timestamp = (int) $timestamp;
		$this->_reference = is_numeric($reference) ? $reference : ee()->localize->now;

		// Initializing to NULL so as not to break the magic __get() method
		$this->_units = array(
			'years'   => NULL,
			'months'  => NULL,
			'weeks'   => NULL,
			'days'    => NULL,
			'hours'   => NULL,
			'minutes' => NULL,
			'seconds' => NULL
		);
	}

	// ------------------------------------------------------------------------

	public function __get($name)
	{
		if (array_key_exists($name, $this->_units))
		{
			return $this->_units[$name];
		}

		if (in_array($name, array('timestamp', 'reference', 'valid_units')))
		{
			return $this->{'_'.$name};
		}

        $trace = debug_backtrace();
        trigger_error(
            'Undefined property via __get(): ' . $name .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line'],
            E_USER_NOTICE);
        return NULL;
	}

	// ------------------------------------------------------------------------

	/**
	 * This calculcates the amount of time that has elapsed between two dates
	 *
	 * @access	public
	 * @param	string[]	$units	An array of units (date parts) to calculate
	 * @return	void
	 */
	public function calculate($units = array('years', 'months', 'weeks', 'days', 'hours', 'minutes', 'seconds'))
	{
		if ( ! is_array($units))
		{
			throw new Exception('We need an array of units to calculate');
		}

		$this->_units = array(
			'years'   => 0,
			'months'  => 0,
			'weeks'   => 0,
			'days'    => 0,
			'hours'   => 0,
			'minutes' => 0,
			'seconds' => 0
		);

		$seconds = array(
			'years'   => 31536000,	// 365 * 24 * 60 * 60
			'months'  => 2628000,	// (365 * 24 * 60 * 60) / 12
			'weeks'   => 604800,	// 7 * 24 * 60 * 60
			'days'    => 86400,		// 24 * 60 * 60
			'hours'   => 3600,		// 60 * 60
			'minutes' => 60,
			'seconds' => 1
		);

		$delta = abs($this->_timestamp - $this->_reference);

		foreach ($this->_valid_units as $unit)
		{
			if (in_array($unit, $units))
			{
				$this->_calculated_units[] = $unit;
				$this->_units[$unit] = (int) floor($delta / $seconds[$unit]);
				$delta -= $this->_units[$unit] * $seconds[$unit];
				$this->_deltas[$unit] = $delta;
			}
		}
	}

	// ------------------------------------------------------------------------

	/**
	 * Generates a human readable relative date string
	 *  	e.g. 'a day'
	 *  	     '5 hours'
	 *
	 * @access	public
	 * @param	int		$depth	Determines how many date parts we use
	 * @return	string	A human readable relative date string
	 */
	public function render($depth = 1)
	{
		$units = array();
		$rounded = FALSE;

		// Check to see if we need to round the smallest displayed unit
		if (is_numeric($depth) AND $depth > 0)
		{
			$non_zero_units = array();
			foreach ($this->_calculated_units as $key)
			{
				if ($this->_units[$key])
				{
					$non_zero_units[] = $key;
				}
			}

			// Rounding needed
			if ($depth < count($non_zero_units))
			{
				$round_to = $non_zero_units[$depth - 1];

				// These are the number of seconds at which we will round up
				// based on the delta from the calculation
				$delta_thresholds = array(
					'years'   => 29808000,	// 345 days
					'months'  => 2160000,	// 25 days
					'weeks'   => 518400,	// 6 days
					'days'    => 79200,		// 22 hours
					'hours'   => 2700,		// 45 minutes
					'minutes' => 45,		// 45 seconds
				);

				if ($this->_deltas[$round_to] >= $delta_thresholds[$round_to])
				{
					$this->_units[$round_to]++;
				}

				$rounded = TRUE;
			}
		}

		// Generate the string from years to seconds
		foreach ($this->_calculated_units as $key)
		{
			if ($this->_units[$key] == 1)
			{
				$units[] = $this->singular.' '.lang(rtrim($key, 's'));
			}
			elseif ($this->_units[$key] > 1)
			{
				$units[] = $this->_units[$key].' '.lang($key);
			}
		}

		if (empty($units))
		{
			$unit = end($this->_calculated_units);
			reset($this->_calculated_units);

			$str = $this->less_than.' '.$this->singular.' '.lang(rtrim($unit, 's'));
		}
		else
		{
			if (is_numeric($depth) AND $depth > 0)
			{
				$units = array_slice($units, 0, $depth);
			}

			// If we have more than one unit on display add an 'and' in for
			// grammar's sake
			if (count($units) > 1)
			{
				$i = count($units) - 1;
				$units[$i] = lang('and').' '.$units[$i];
			}

			// Add commas if we have more than 2 units to display
			if (count($units) > 2)
			{
				$str = implode(', ', $units);
			}
			else
			{
				$str = implode(' ', $units);
			}

		}

		if ($rounded)
		{
			$str = $this->about.' '.$str;
		}

		if ($this->_timestamp <= $this->_reference)
		{
			$str = str_replace('%s', $str, $this->past);
		}
		else
		{
			$str = str_replace('%s', $str, $this->future);
		}

		return $str;
	}

}

// END Relative_Date class

/* End of file Relative_Date.php */
/* Location: ./system/expressionengine/libraries/Relative_date.php */