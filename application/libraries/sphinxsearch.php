<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

define('SPHINXSEARCH_SPARK_HOME', dirname(dirname(__FILE__)));

class_exists('SphinxClient') or require_once(SPHINXSEARCH_SPARK_HOME.'/third_party/sphinxapi.php');

class Sphinxsearch {

  private $CI;
  private $client;
  
  function __construct($config = array())
  {
    $this->CI =& get_instance();
    $this->CI->load->helper('inflector');
    $this->config = $config;
    $this->client = new SphinxClient();
    $this->initialize();
  }
  
  function initialize($config = array())
  {
    $this->config = array_merge($this->config, $config);
    foreach ($this->config as $setting => $value) 
    {
      $setter = "set_{$setting}";
      if ( ! is_array($value)) $value = array($value);
      call_user_func_array(array($this, $setter), $value);
    }
  }
  
  function get_filters() {
	  return $this->client->_filters;
  }
  
  //delegates all method calls to sphinx client providing ci method naming convention
  function __call($method, $args)
  {
    $sphinx_method = ucfirst(camelize($method));
    if (method_exists($this->client, $sphinx_method))
    {
      return call_user_func_array(array($this->client, $sphinx_method), $args);
    }
  }

}
