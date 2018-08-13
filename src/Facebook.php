<?php
/**
 * Part of the Joomla Framework Facebook Package
 *
 * @copyright  Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Facebook;

use Joomla\Http\Http;

/**
 * Joomla Framework class for interacting with a Facebook API instance.
 *
 * @since       1.0
 * @deprecated  The joomla/facebook package is deprecated
 */
class Facebook
{
	/**
	 * Options for the Facebook object.
	 *
	 * @var    array
	 * @since  1.0
	 */
	protected $options;

	/**
	 * The HTTP client object to use in sending HTTP requests.
	 *
	 * @var    Http
	 * @since  1.0
	 */
	protected $client;

	/**
	 * The OAuth client.
	 *
	 * @var    OAuth
	 * @since  1.0
	 */
	protected $oauth;

	/**
	 * Facebook API object for user.
	 *
	 * @var    User
	 * @since  1.0
	 */
	protected $user;

	/**
	 * Facebook API object for status.
	 *
	 * @var    Status
	 * @since  1.0
	 */
	protected $status;

	/**
	 * Facebook API object for checkin.
	 *
	 * @var    Checkin
	 * @since  1.0
	 */
	protected $checkin;

	/**
	 * Facebook API object for event.
	 *
	 * @var    Event
	 * @since  1.0
	 */
	protected $event;

	/**
	 * Facebook API object for group.
	 *
	 * @var    Group
	 * @since  1.0
	 */
	protected $group;

	/**
	 * Facebook API object for link.
	 *
	 * @var    Link
	 * @since  1.0
	 */
	protected $link;

	/**
	 * Facebook API object for note.
	 *
	 * @var    Note
	 * @since  1.0
	 */
	protected $note;

	/**
	 * Facebook API object for post.
	 *
	 * @var    Post
	 * @since  1.0
	 */
	protected $post;

	/**
	 * Facebook API object for comment.
	 *
	 * @var    Comment
	 * @since  1.0
	 */
	protected $comment;

	/**
	 * Facebook API object for photo.
	 *
	 * @var    Photo
	 * @since  1.0
	 */
	protected $photo;

	/**
	 * Facebook API object for video.
	 *
	 * @var    Video
	 * @since  1.0
	 */
	protected $video;

	/**
	 * Facebook API object for album.
	 *
	 * @var    Album
	 * @since  1.0
	 */
	protected $album;

	/**
	 * Constructor.
	 *
	 * @param   OAuth  $oauth    OAuth client.
	 * @param   array  $options  Facebook options array.
	 * @param   Http   $client   The HTTP client object.
	 *
	 * @since   1.0
	 */
	public function __construct(OAuth $oauth = null, $options = array(), Http $client = null)
	{
		$this->oauth   = $oauth;
		$this->options = $options;
		$this->client  = $client;

		// Setup the default API url if not already set.
		if (!isset($this->options['api.url']))
		{
			$this->options['api.url'] = 'https://graph.facebook.com/';
		}
	}

	/**
	 * Magic method to lazily create API objects
	 *
	 * @param   string  $name  Name of property to retrieve
	 *
	 * @return  AbstractFacebookObject  Facebook API object (status, user, friends etc).
	 *
	 * @since   1.0
	 * @throws  \InvalidArgumentException If $name is not a valid sub class.
	 */
	public function __get($name)
	{
		$class = __NAMESPACE__ . '\\' . ucfirst(strtolower($name));

		if (class_exists($class) && property_exists($this, $name))
		{
			if (isset($this->$name) == false)
			{
				$this->$name = new $class($this->options, $this->client, $this->oauth);
			}

			return $this->$name;
		}

		throw new \InvalidArgumentException(sprintf('Argument %s produced an invalid class name: %s', $name, $class));
	}

	/**
	 * Get an option from the Facebook instance.
	 *
	 * @param   string  $key  The name of the option to get.
	 *
	 * @return  mixed  The option value.
	 *
	 * @since   1.0
	 */
	public function getOption($key)
	{
		return isset($this->options[$key]) ? $this->options[$key] : null;
	}

	/**
	 * Set an option for the Facebook instance.
	 *
	 * @param   string  $key    The name of the option to set.
	 * @param   mixed   $value  The option value to set.
	 *
	 * @return  Facebook  This object for method chaining.
	 *
	 * @since   1.0
	 */
	public function setOption($key, $value)
	{
		$this->options[$key] = $value;

		return $this;
	}
}
