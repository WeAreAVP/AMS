<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter Autoloader Hook Class for Base Controllers
 *
 * The file is part of "CodeIgniter Base Controllers" package that
 * aims to simplify the development of controllers by introducing
 * and autoloading a few base controller classes to the application.
 *
 * Make sure that you already read the installation instruction at
 * its repository README file: https://github.com/sepehr/ci-base-controllers
 *
 * In order for this package to perform correctly:
 *
 * 1. These files should exist:
 * - application/core/MY_Controller.php
 * - application/core/Public_Controller.php (a base controller - optional)
 * - application/core/Admin_Controller.php  (a base controller - optional)
 * - application/hooks/CI_Autoloader.php
 *
 * 2. Hooks must be enabled in application config.php file.
 *
 * 3. A pre_system hook must already be registered in application hooks.php config file:
 * $hook['pre_system'] = array(
 *     'class'    => 'CI_Autoloader',
 *           'function' => 'register',
 *           'filename' => 'CI_Autoloader.php',
 *           'filepath' => 'hooks',
 *           'params'   => array(APPPATH . 'base/')
 * );
 *
 * @package                CodeIgniter
 * @author                Sepehr Lajevardi <me@sepehr.ws>
 * @copyright        Copyright (c) 2012 Sepehr Lajevardi.
 * @license                http://codeigniter.com/user_guide/license.html
 * @link                https://github.com/sepehr/ci-base-controllers
 * @version         Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CI_Autoloader Hook Class
 *
 * @package                CodeIgniter
 * @category
 * @author                Sepehr Lajevardi <me@sepehr.ws>
 * @link                https://github.com/sepehr/ci-base-controllers
 * @see                        http://highermedia.com/articles/nuts_bolts/codeigniter_base_classes_revisited
 */
class CI_Autoloader {

        private $_include_paths = array();

        // --------------------------------------------------------------------

        /**
         * Register the autoloader function.
         *
         * @param  array include paths
         * @return void
         */
        public function register(array $paths = array())
        {
                $this->_include_paths = $paths;
                spl_autoload_register(array($this, 'autoloader'));
        }

        // --------------------------------------------------------------------

        /**
         * Autoload base classes.
         *
         * @param  string class to load
         * @return void
         */
        public function autoloader($class)
        {
                foreach($this->_include_paths as $path)
                {
                        $filepath = $path . $class . EXT;

                        if(! class_exists($class, FALSE) AND is_file($filepath))
                        {
                                include_once $filepath;
                                break;
                        }
                }
        }

}
// End of MY_Controller class

/* End of file CI_Autoloader.php */
/* Location: ./application/hooks/CI_Autoloader.php */