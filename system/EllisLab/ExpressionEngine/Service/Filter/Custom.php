<?php
namespace EllisLab\ExpressionEngine\Service\Filter;

/**
 * ExpressionEngine - by EllisLab
 *
 * @package		ExpressionEngine
 * @author		EllisLab Dev Team
 * @copyright	Copyright (c) 2003 - 2014, EllisLab, Inc.
 * @license		http://ellislab.com/expressionengine/user-guide/license.html
 * @link		http://ellislab.com
 * @since		Version 3.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * ExpressionEngine Custom Filter Class
 *
 * This provides a custom "one-off" filter.
 *
 * @package		ExpressionEngine
 * @category	Service
 * @author		EllisLab Dev Team
 * @link		http://ellislab.com
 */
class Custom extends Filter {

	/**
	 * Constructor
	 *
	 * @see Filter\Filter::options For the format of the $options array
	 *
	 * @param string $name    The name="" attribute for this filter
	 * @param string $label   A language key to be used for the display label
	 * @param array  $options An associative array to use to build the option
	 *                        list.
	 * @return void
	 */
	public function __construct($name, $label, array $options)
	{
		$this->name = $name;
		$this->label = $label;
		$this->options = $options;
	}
	/**
	 * Sets the placeholder value for this filter
	 *
	 * @param string $placeholder The value to use for the placeholder
	 * @return void
	 */
	public function setPlaceholder($placeholder)
	{
		$this->placeholder = $placeholder;
	}

	/**
	 * Disables the custom value by setting has_custom_value to False.
	 * @see Filter::has_custom_value
	 * @return void
	 */
	public function disableCustomValue()
	{
		$this->has_custom_value = FALSE;
	}

}
// EOF