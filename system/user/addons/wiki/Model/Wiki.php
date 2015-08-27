<?php

namespace User\addons\Wiki\Model;

use EllisLab\ExpressionEngine\Service\Model\Model;

/**
 * ExpressionEngine - by EllisLab
 *
 * @package		ExpressionEngine
 * @author		EllisLab Dev Team
 * @copyright	Copyright (c) 2003 - 2014, EllisLab, Inc.
 * @license		https://ellislab.com/expressionengine/user-guide/license.html
 * @link		http://ellislab.com
 * @since		Version 3.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * ExpressionEngine Wiki Model
 *
 * A model representing a Wiki in the Wiki module.
 *
 * @package		ExpressionEngine
 * @subpackage	Wiki Module
 * @category	Model
 * @author		EllisLab Dev Team
 * @link		http://ellislab.com
 */
class Wiki extends Model {

	protected static $_primary_key = 'wiki_id';
	protected static $_table_name = 'wikis';

	protected static $_typed_columns = array(
		'wiki_moderation_emails'     => 'commaDelimited',
		'wiki_admins'     => 'pipeDelimited',
		'wiki_users'     => 'pipeDelimited'
	);

	protected static $_validation_rules = array(
		'wiki_label_name'	     => 'required|unique',
		'wiki_short_name'       => 'required|validateShortName|unique',
		'wiki_moderation_emails'   => 'validateEmails',
		'wiki_upload_dir' => 'required',
		'wiki_users' => 'required',
		'wiki_admins' => 'required',
		'wiki_html_format' => 'required',
		'wiki_text_format' => 'required',
		'wiki_revision_limit' => 'is_natural',
		'wiki_author_limit' => 'is_natural'
	);

/*

	protected static $_relationships = array(
		'WikiNamespaces' => array(
			'type' => 'hasMany',
			'model' => 'WikiNamespace'
		),
		'Categories' => array(
			'type'  => 'hasMany',
			'model' => 'Category'
		),
		'Pages' => array(
			'type'  => 'hasMany',
			'model' => 'Page'
		),
		'Uploads' => array(
			'type'  => 'hasMany',
			'model' => 'Upload'
		)
	);
*/

	protected static $_relationships = array(
		'WikiNamespaces' => array(
			'type' => 'hasMany',
			'model' => 'WikiNamespace'
		)
	);

	protected $wiki_id;
	protected $wiki_label_name;
	protected $wiki_short_name;
	protected $wiki_text_format;
	protected $wiki_html_format;
	protected $wiki_upload_dir;
	protected $wiki_admins;
	protected $wiki_users;
	protected $wiki_revision_limit;
	protected $wiki_author_limit;
	protected $wiki_moderation_emails;



	/**
	 * Ensures fields with multiple emails contain valid emails
	 */
	public function validateEmails($key, $value, $params, $rule)
	{
		foreach($value as $email)
		{
			if (trim($email) != '' && (bool) filter_var($email, FILTER_VALIDATE_EMAIL) === FALSE)
			{
				return 'valid_emails';
			}
		}

		return TRUE;
	}
	
	public function validateShortName($key, $value, $params, $rule)
	{
		if (preg_match('/[^a-z0-9\-\_]/i', $value))
		{
			return 'invalid_short_name';
		}

		return TRUE;
	}

}
