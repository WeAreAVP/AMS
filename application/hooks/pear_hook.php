<?php

class Pear_hook
{

	private $_include_paths = array();

	public function initialize(array $paths = array())
	{
		$this->_include_paths = $paths;

		spl_autoload_register(array($this, 'autoloader'));
	}

	// --------------------------------------------------------------------

	/**
	 * Autoload base classes.
	 *
	 * @access public
	 * @param string class to load
	 * @return void
	 */
	public function autoloader($class)
	{
		foreach ($this->_include_paths as $path)
		{
			$filepath = $path . $class . EXT;

			if ( ! class_exists($class, FALSE) AND is_file($filepath))
			{
				include_once($filepath);

				break;
			}
		}
	}

	// --------------------------------------------------------------------
}
