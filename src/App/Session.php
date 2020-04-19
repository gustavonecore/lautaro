<?php namespace Leftaro\App;

use ArrayAccess;

/**
 * Session Class
 *
 * Class for handling the session
 */
class Session implements ArrayAccess
{
	private static $instance = null;

	/**
	 * Create a new session
	 *
	 * @return Session
	 */
	public static function init()
	{
		if (self::$instance === null)
		{
			session_start();
			self::$instance = new Session;
		}

		return self::$instance;
	}

	/**
	 * Get a session value
	 *
	 * @param string    $key  Key of the session value
	 *
	 * @return mixed    Value of the key
	 */
	public function __get($key)
	{
		return $this->get($key);
	}

	/**
	 * Set a session value
	 *
	 * @param string    $key  Key of the session value
	 * @param string    $val  Value of the session key
	 *
	 * @return boolean
	 */
	public function __set($key, $val)
	{
		return $this->set($key, $val);
	}

	/**
	 * Get a session value
	 *
	 * @param string    $key  Key of the session value
	 *
	 * @return mixed    Value of the key
	 */
	public function offsetGet($key)
	{
		return $this->get($key);
	}

	/**
	 * Set a session value
	 *
	 * @param string    $key  Key of the session value
	 * @param string    $val  Value of the session key
	 *
	 * @return boolean
	 */
	public function offsetSet($key, $val)
	{
		$this->set($key, $val);
	}

	/**
	 * Test if a key was stored in session
	 *
	 * @param string    $key  Key of the session value
	 *
	 * @return boolean
	 */
	public function offsetExists($key)
	{
		return array_key_exists($key, $_SESSION);
	}

	/**
	 * Delete a session record
	 *
	 * @param string    $key  Key of the session value
	 */
	public function offsetUnset($key)
	{
		$this->delete($key);
	}

	/**
	 * Set a session value
	 *
	 * @param string    $key  Key of the session value
	 * @param string    $val  Value of the session key
	 *
	 * @return boolean
	 */
	public function set($key, $val)
	{
		if ($val === null)
		{
			return $this->delete($key);
		}
		else
		{
			$_SESSION[$key] = $val;
			return true;
		}

		return false;
	}

	/**
	 * Get a session value
	 *
	 * @param string    $key  Key of the session value
	 *
	 * @return mixed    Value of the key
	 */
	public function get($key)
	{
		return array_key_exists($key, $_SESSION) ? $_SESSION[$key] : null;
	}

	/**
	 * Delete a session record
	 *
	 * @param string | null    $key  Key of the session value
	 *
	 * @return boolean
	 */
	public function delete($key = null)
	{
		if ($key)
		{
			if (array_key_exists($key, $_SESSION))
			{
				unset($_SESSION[$key]);
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			$_SESSION = [];
			return true;
		}

		return false;
	}

	/**
	 * Get a session value and then remove from the session array
	 *
	 * @param string    $key  Key of the session value
	 *
	 * @return mixed    Value of the key
	 */
	public function getOnce($key)
	{
		$ret = null;

		if (array_key_exists($key, $_SESSION))
		{
			$ret = $_SESSION[$key];
			unset($_SESSION[$key]);
		}

		return $ret;
	}

	/**
	 * Check if a key exists.
	 *
	 * @param string    $key  Key of the session value
	 *
	 * @return bool True if key exists.
	 */
	public function has(string $key)
	{
		return array_key_exists($key, $_SESSION);
	}
}