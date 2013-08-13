<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

$route = array();

$route['myseo/admin/index'] = 'admin';
$route['myseo/admin/pages(:any)?'] = 'admin$1';
$route['myseo/admin/posts(:any)?'] = 'admin$1';
$route['myseo/admin/options(:any)?'] = 'admin_options$1';