<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Myseo extends Module
{
    public $version = '2.0.3';

    public function info()
    {
        return array(
            'name' => array(
                'en' => 'MySEO'
            ),
            'description' => array(
                'en' => 'SEO solution for PyroCMS'
            ),
            'backend' => true,
            'menu' => 'content',
            'sections' => array(
                'pages' => array(
                    'name' => 'myseo:pages',
                    'uri' => 'admin/myseo/pages'
                ),
                'posts' => array(
                    'name' => 'myseo:posts',
                    'uri' => 'admin/myseo/posts'
                ),
                'options' => array(
                    'name' => 'myseo:options',
                    'uri' => 'admin/myseo/options'
                )
            )
        );
    }

    public function install()
    {
        $this->dbforge->drop_table('myseo_options');
        $this->dbforge->drop_table('myseo_filters');
        $this->dbforge->drop_table('myseo_pages_index');
        $this->dbforge->drop_table('myseo_posts_meta');
        $this->dbforge->drop_table('myseo_posts_index');

        $tables = array(
            'myseo_options' => array(
                'max_title_len' => array('type' => 'INT', 'constraint' => 11),
                'max_desc_len' => array('type' => 'INT', 'constraint' => 11),
                'pagination_limit' => array('type' => 'INT', 'constraint' => 11),
                'auto_collapse' => array('type' => 'INT', 'constraint' => 11),
            ),
            'myseo_filters' => array(
                'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true),
                'type' => array('type' => 'VARCHAR', 'constraint' => 32),
                'name' => array('type' => 'VARCHAR', 'constraint' => 32),
                'value' => array('type' => 'VARCHAR', 'constraint' => 250),
            ),
            'myseo_index' => array(
                'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true),
                'item_id' => array('type' => 'INT', 'constraint' => 11),
                'hash' => array('type' => 'VARCHAR', 'constraint' => 32),
            ),
            'myseo_posts_meta' => array(
                'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true),
                'post_id' => array('type' => 'INT', 'constraint' => 11),
                'title' => array('type' => 'VARCHAR', 'constraint' => 250, 'null' => true),
                'keywords' => array('type' => 'VARCHAR', 'constraint' => 32, 'null' => true),
                'description' => array('type' => 'TEXT', 'null' => true),
                'no_index' => array('type' => 'INT', 'constraint' => 11, 'null' => true),
                'no_follow' => array('type' => 'INT', 'constraint' => 11, 'null' => true),
            ),
        );

        if ( ! $this->install_tables($tables))
        {
            return false;
        }

        $options = array(
            'max_title_len' => 69,
            'max_desc_len' => 156,
            'pagination_limit' => 10,
            'auto_collapse' => 1,
        );

        if ( ! $this->db->insert('myseo_options', $options))
        {
            return false;
        }

        $filters = array(
            'pages' => array(
                'by_title' => '',
                'by_uri' => '',
                'hide_drafts' => 1,
                'top_page' => 0
            ),
            'posts' => array(
                'by_title' => '',
                'by_slug' => '',
                'category' => 0,
                'hide_drafts' => 1,
            )
        );

        foreach ($filters as $type => $filter)
        {
            foreach ($filter as $name => $value)
            {
                $this->db->insert('myseo_filters', array(
                    'type' => $type,
                    'name' => $name,
                    'value' => $value
                ));
            }
        }

        return true;
    }

    public function uninstall()
    {
        $this->dbforge->drop_table('myseo_options');
        $this->dbforge->drop_table('myseo_filters');
        $this->dbforge->drop_table('myseo_pages_index');
        $this->dbforge->drop_table('myseo_posts_meta');
        $this->dbforge->drop_table('myseo_posts_index');

        return true;
    }

    public function upgrade($old_version)
    {
        return true;
    }
}