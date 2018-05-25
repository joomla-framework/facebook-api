<?php
/**
 * @copyright  Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Facebook\Tests;

use Joomla\Facebook\OAuth;
use Joomla\Test\TestHelper;

/**
 * Test class for Joomla\Facebook\OAuth.
 *
 * @since  1.0
 */
class OAuthTest extends FacebookTestCase
{
	/**
	 * @var    \Joomla\Application\AbstractWebApplication  The application object to send HTTP headers for redirects.
	 */
	protected $application;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @access protected
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function setUp()
	{
		$_SERVER['HTTP_HOST'] = 'example.com';
		$_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0';
		$_SERVER['REQUEST_URI'] = '/index.php';
		$_SERVER['SCRIPT_NAME'] = '/index.php';

		$this->options = array();
		$this->client = $this->getMockBuilder('Joomla\\Http\\Http')->getMock();
		$this->input = $this->getMockBuilder('Joomla\\Input\\Input')->getMock();

		$this->application = $this->getMockForAbstractClass('Joomla\\Application\\AbstractWebApplication');
		$this->object = new OAuth($this->options, $this->client, $this->input, $this->application);
	}

	/**
	 * Tests the setScope method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testSetScope()
	{
		$this->object->setScope('read_stream');

		$value = TestHelper::getValue($this->object, 'options');

		$this->assertThat(
			$value['scope'],
			$this->equalTo('read_stream')
		);
	}

	/**
	 * Tests the getScope method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetScope()
	{
		TestHelper::setValue(
			$this->object, 'options', array(
				'scope' => 'read_stream'
			)
		);

		$this->assertThat(
			$this->object->getScope(),
			$this->equalTo('read_stream')
		);
	}
}
