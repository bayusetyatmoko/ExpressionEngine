<?php
/**
 * This source file is part of the open source project
 * ExpressionEngine (https://expressionengine.com)
 *
 * @link      https://expressionengine.com/
 * @copyright Copyright (c) 2003-2018, EllisLab, Inc. (https://ellislab.com)
 * @license   https://expressionengine.com/license Licensed under Apache License, Version 2.0
 */

namespace EllisLab\Tests\ExpressionEngine\Controllers\Utilities;

use PHPUnit\Framework\TestCase;

class ExtensionsTest extends TestCase {

	public static function setUpBeforeClass()
	{
		require_once(APPPATH.'core/Controller.php');
	}

	public function testRoutableMethods()
	{
		$controller_methods = array();

		foreach (get_class_methods('EllisLab\ExpressionEngine\Controller\Utilities\Extensions') as $method)
		{
			$method = strtolower($method);
			if (strncmp($method, '_', 1) != 0)
			{
				$controller_methods[] = $method;
			}
		}

		sort($controller_methods);

		$this->assertEquals(array('index'), $controller_methods);
	}

}
