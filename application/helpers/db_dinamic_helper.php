<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */


if ( ! function_exists('switch_db_dinamico'))
{
	function switch_db_dinamico($username,$password,$db_name)
		{
			$config_app['hostname'] = 'localhost';
			$config_app['username'] = $username;
			$config_app['password'] = $password;
			$config_app['database'] = $db_name;
			$config_app['dbdriver'] = 'mysqli';
			$config_app['dbprefix'] = '';
			$config_app['pconnect'] = FALSE;
			$config_app['db_debug'] = FALSE;
			return $config_app;
		}
}

// ------------------------------------------------------------------------
/* End of file language_helper.php */
/* Location: ./system/helpers/language_helper.php */