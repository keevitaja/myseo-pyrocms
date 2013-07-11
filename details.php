<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * MySeo module
 * 2013
 *
 * https://github.com/keevitaja/myseo-pyrocms
 *
 * @package     myseo
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
                'en' => 'Edit SEO related meta information from one page.'
            ),
            'frontend' => false,
            'backend' => true,
            'menu' => 'content',
            'sections' => array(
                'pages' => array(
                    'name' => 'Pages',
                    'uri' => 'admin/myseo'
                ),
                'options' => array(
                    'name' => 'Options',
                    'uri' => 'admin/myseo/options'
                )
            )
        );
    }

    public function install()
    {
        $this->dbforge->drop_table('myseo_options');

        $tables = array(
            'myseo_options' => array(
                'max_title_len' => array('type' => 'INT', 'constraint' => 11),
                'max_desc_len' => array('type' => 'INT', 'constraint' => 11),
                'pagination_limit' => array('type' => 'INT', 'constraint' => 11),
                'hide_drafts' => array('type' => 'INT', 'constraint' => 11),
                'filter_by_title' => array('type' => 'VARCHAR', 'constraint' => 100, 'null' => true),
                'filter_by_top_page' => array('type' => 'INT', 'constraint' => 11),
                'filter_by_uri' => array('type' => 'VARCHAR', 'constraint' => 30, 'null' => true),
                'auto_hide_rows' => array('type' => 'INT', 'constraint' => 11),
                'auto_hide_filters' => array('type' => 'INT', 'constraint' => 11),
            ),
            'myseo_tmp_pages' => array(
                'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true),
                'page_id' => array('type' => 'INT', 'constraint' => 11),
                'hash' => array('type' => 'VARCHAR', 'constraint' => 32),
            )
        );

        if ( ! $this->install_tables($tables))
        {
            return false;
        }

        $options = array(
            'max_title_len' => 70,
            'max_desc_len' => 170,
            'pagination_limit' => 10,
            'hide_drafts' => 1,
            'filter_by_top_page' => -1,
            'auto_hide_rows' => 1,
            'auto_hide_filters' => 0,
        );

        if ( ! $this->db->insert('myseo_options', $options))
        {
            return false;
        }

        return true;
    }

    public function uninstall()
    {
        $this->dbforge->drop_table('myseo_options');
        $this->dbforge->drop_table('myseo_tmp_pages');

        return true;
    }

    public function upgrade($old_version)
    {
        // update manually
        // overwrite the files and reinstall module
        return false;
    }
}