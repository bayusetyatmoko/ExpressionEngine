<?php

namespace EllisLab\ExpressionEngine\Model\Site;

use EllisLab\ExpressionEngine\Service\Model\Model;

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
 * ExpressionEngine Site Table
 *
 * The Site model stores preference sets for each site in this installation
 * of ExpressionEngine.  Each site can have a completely different set of
 * settings and prefereces.
 *
 * @package		ExpressionEngine
 * @subpackage	Site
 * @category	Model
 * @author		EllisLab Dev Team
 * @link		http://ellislab.com
 */
class Site extends Model {

	protected static $_primary_key = 'site_id';
	protected static $_table_name = 'sites';

	protected static $_columns = array(
		'ChannelPreferences' => 'site_channel_preferences',
		'MailingListPreferences' => 'site_mailinglist_preferences',
		'MemberPreferences' => 'site_member_preferences',
		'SystemPreferences' => 'site_system_preferences',
		'TemplatePreferences' => 'site_template_preferences'
	);

	protected static $_relationships = array(
		'GlobalVariables' => array(
			'model' => 'GlobalVariable',
			'type' => 'hasMany'
		),
		'Stats' => array(
			'type' => 'HasOne'
		),
		'TemplateGroups' => array(
			'model' => 'TemplateGroup',
			'type' => 'hasMany'
		),
	);

	// Properties
	protected $site_id;
	protected $site_label;
	protected $site_name;
	protected $site_description;
	protected $site_system_preferences;
	protected $site_mailinglist_preferences;
	protected $site_member_preferences;
	protected $site_template_preferences;
	protected $site_channel_preferences;
	protected $site_bootstrap_checksums;
}