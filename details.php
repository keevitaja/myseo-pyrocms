<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * MySeo module
 * 2013
 *
 * https://github.com/keevitaja/myseo-pyrocms
 *
 * @package     myseo/core
 * @author      Tanel Tammik <keevitaja@gmail.com>
 * @version     master
 *
 */
class Module_Myseo extends Module {

    public $version = '1.0.0';

    public function info()
    {
        return array(
            'name' => array(
                'en' => 'MySeo'
            ),
            'description' => array(
                'en' => 'Edit headers meta data from one page'
            ),
            'frontend'	=> false,
            'backend'	=> true,
            'menu'		=> 'content'
        );
    }

    public function install()
    {
        // main controller checks each load if variables data is present

        return true;
    }

    public function uninstall()
    {
        // uninstall variables data
        return $this->db->where('name', 'myseo_settings')->delete('variables');
    }

    public function upgrade($old_version)
    {
        return false;
    }
}