<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Myseo - model
 *
 * Copyright (c) 2013
 * http://github.com/keevitaja/sidenav-pyrocms
 *
 * @package     myseo
 * @author      Tanel Tammik <keevitaja@gmail.com>
 * @copyright   Copyright (c) 2013
 * @version     master
 * @link        http://github.com/keevitaja/myseo-pyrocms
 *
 */

class Myseo_m extends MY_Model
{
    // pages table fields used by myseo plugin
    private $select_fields = '
        pages.id,
        pages.title,
        pages.uri,
        pages.meta_title,
        pages.meta_keywords,
        pages.meta_description,
        pages.meta_robots_no_index,
        pages.meta_robots_no_follow
    ';

    // gets all pages recursively
    public function get_pages_recursive($page_id = 0, $pages = array())
    {
        // loops children under given parent
        foreach ($this->get_children($page_id) as $page)
        {
            $pages[] = $page;

            $children = $this->get_children($page['id']);

            // if page has children, do recursion
            if ( ! empty($children))
            {
                $pages = $this->get_pages_recursive($page['id'], $pages);
            }
        }

        return $pages;
    }

    // gets pages for view, includes filter and offset
    // not very fast in case of 5000 pages
    public function get_pages($page_id, $offset)
    {
        $filters = $this->get_options();
        $hash = md5(time() + microtime());

        // add top page
        if ($filters['filter_by_top_page'] != 0)
        {
            $page = $this->db
                ->select($this->select_fields)
                ->where('id', $page_id)
                ->get('pages')->row_array();

            // insert top page into tmp table
            if ( ! empty($page))
            {
                $this->db->insert('myseo_tmp_pages', array('page_id' => $page['id'], 'hash' => $hash));
            }

            unset($page);

        }

        // TODO: this is very slow
        // stores all pages in tmp table for later use
        $pages = $this->get_pages_recursive($page_id);

        foreach ($pages as $page)
        {
            $this->db->insert('myseo_tmp_pages', array('page_id' => $page['id'], 'hash' => $hash));
        }

        unset($pages);

        // count all rows for pagination
        $count_rows = $this->get_filtered_data($filters, $hash, $offset);

        // gets filtered rows
        $pages_raw = $this->get_filtered_data($filters, $hash, $offset, false);

        // deletes tmp table entries
        $this->db->where('hash', $hash)->delete('myseo_tmp_pages');

        $pages = array();

        // fetches keywords
        foreach ($pages_raw as $page)
        {
            $page['meta_keywords'] = Keywords::get_string($page['meta_keywords']);
            $pages[] = $page;
        }

        // returns pages and pages count
        return array($pages, $count_rows);
    }

    // counts all gets pages taking filters into account
    // ment to be used on in get_pages()
    public function get_filtered_data($filters, $hash, $offset, $count = true)
    {
        $select = ($count) ? 'COUNT(*) as count' : $this->select_fields;

        $this->db->select($select);

        if ($filters['hide_drafts'] == 1)
        {
            $this->db->where('pages.status !=', 'draft');
        }

        // show only if in title
        if ( ! empty($filters['filter_by_title']))
        {
            $this->db->like('pages.title', $filters['filter_by_title']);
        }

        // show only if in slug, currently can't match slashes. waiting for wisdom
        if ( ! empty($filters['filter_by_uri']))
        {
            $this->db->like('pages.uri', $filters['filter_by_uri']);
        }

        // count or fetch
        if ($count)
        {
            $result = $this->db
                ->where('myseo_tmp_pages.hash', $hash)
                ->join('pages', 'pages.id=myseo_tmp_pages.page_id', 'left')
                ->get('myseo_tmp_pages')->row()->count;
        }
        else
        {
            $result = $this->db
                ->where('myseo_tmp_pages.hash', $hash)
                ->join('pages', 'pages.id=myseo_tmp_pages.page_id', 'left')
                ->order_by('myseo_tmp_pages.id')
                ->get('myseo_tmp_pages', $filters['pagination_limit'], $offset)->result_array();
        }

        return $result;
    }

    // gets all children
    public function get_children($page_id)
    {
         return $this->db
            ->select('id')
            ->where('parent_id', $page_id)
            ->order_by('order')
            ->get('pages')->result_array();
    }

    // gets first level pages for html select
    public function get_top_pages()
    {
        $pages_a = $this->db
            ->select('id, title')
            ->where('parent_id', 0)
            ->order_by('order')
            ->get('pages')->result_array();

        $pages = array(
            '-1' => lang('myseo:form_dropdown_display_none'),
            '0' => lang('myseo:form_dropdown_display_all')
        );

        // make form_dropdown eat pages array
        foreach ($pages_a as $page)
        {
            $id = $page['id'];
            $title = $page['title'];

            $pages[$id] = $title;
        }

        return $pages;
    }

    // gets options
    public function get_options()
    {
        return $this->db->get('myseo_options')->row_array();
    }

    // updates options
    public function update_options($options)
    {
        return $this->db->set($options)->update('myseo_options');
    }
}