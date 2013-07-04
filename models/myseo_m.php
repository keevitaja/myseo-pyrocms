<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * MySeo model
 * 2013
 *
 * @package     myseo/core
 * @author      Tanel Tammik <keevitaja@gmail.com>
 * @version     master
 *
 */
class Myseo_m extends MY_Model
{
    private $settings;

    private $select_fields;

    public function __construct()
    {
        parent::__construct();

        $this->settings = $this->get_settings();

        $this->select_fields = '
            id,
            title,
            uri,
            meta_title,
            meta_keywords,
            meta_description,
            meta_robots_no_index,
            meta_robots_no_follow
        ';
    }

    // gets all pages recursive, order by order
    public function get_all_pages_r($page_id = 0, $pages = array())
    {
        foreach ($this->get_children($page_id) as $page)
        {
            $page['meta_keywords'] = Keywords::get_string($page['meta_keywords']);

            $pages[] = $page;

            $children = $this->get_children($page['id']);

            if ( ! empty($children))
            {
                $pages = $this->get_all_pages_r($page['id'], $pages);
            }
        }

        return $pages;
    }

    // gets all pages
    public function get_all_pages($page_id)
    {
        $pages = $this->get_all_pages_r($page_id);

        // if top page is defined, add page it self
        if ($this->settings['top_page'] != 0)
        {
            $this->db
                ->select($this->select_fields)
                ->where('id', $page_id);

            // show only if in title
            if ( ! empty($this->settings['filter_by_title']))
            {
                $this->db->like('title', $this->settings['filter_by_title']);
            }

            $page = $this->db->get('pages')->result_array();

            if ( ! empty($page))
            {
                $page[0]['meta_keywords'] = Keywords::get_string($page[0]['meta_keywords']);
            }

            $pages = array_merge($page, $pages);
        }

        return $pages;
    }

    // gets first level pages for html select
    public function get_top_pages()
    {
        $pages_a = $this->db
            ->select('id, title')
            ->where('parent_id', 0)
            ->order_by('order')
            ->get('pages')->result_array();

        $pages = array('0' => 'Select all');

        foreach ($pages_a as $page)
        {
            $id = $page['id'];
            $title = $page['title'];

            $pages[$id] = $title;
        }

        return $pages;
    }

    // gets all children
    public function get_children($page_id)
    {
        $this->db
            ->select($this->select_fields)
            ->where('parent_id', $page_id);

        // hide drafts
        if ($this->settings['hide_drafts'] == 1)
        {
            $this->db->where('status !=', 'draft');
        }

        // show only if in title
        if ( ! empty($this->settings['filter_by_title']))
        {
            $this->db->like('title', $this->settings['filter_by_title']);
        }

        return $this->db->order_by('order')
            ->get('pages')->result_array();
    }

    // get settings
    public function get_settings()
    {
        $settings = $this->db
            ->select('data')
            ->where('name', 'myseo_settings')
            ->get('variables')->row_array();

        if ( ! empty($settings))
        {
            $settings = json_decode($settings['data'], true);
        }

        return $settings;
    }

    // set settings
    public function set_settings($settings)
    {
        return $this->db->insert('variables', array(
            'name' => 'myseo_settings',
            'data' => json_encode($settings)
        ));
    }
}