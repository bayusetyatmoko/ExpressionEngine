<?php
/**
 * This source file is part of the open source project
 * ExpressionEngine (https://expressionengine.com)
 *
 * @link      https://expressionengine.com/
 * @copyright Copyright (c) 2003-2018, EllisLab, Inc. (https://ellislab.com)
 * @license   https://expressionengine.com/license Licensed under Apache License, Version 2.0
 */

/**
 * Relationship Module
 */
class Relationship {

	/**
	 * AJAX endpoint for filtering a Relationship field on the publish form
	 */
	public function entryList()
	{
		ee()->load->library('EntryList');
		ee()->output->send_ajax_response(ee()->entrylist->ajaxFilter());
	}
}

// EOF
