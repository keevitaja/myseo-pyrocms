<?php defined('BASEPATH') OR exit('No direct script access allowed');

// consider model as a driver for module or a stream

class Myseo_pages_m extends MY_Model
{
    // fields to select from pages table
    private $select_fields = '
        pages.id,
        pages.title as the_title,
        pages.uri as the_uri,
        pages.meta_title,
        pages.meta_keywords,
        pages.meta_description,
        pages.meta_robots_no_index,
        pages.meta_robots_no_follow
    ';

    private $options;

    public function __construct()
    {
        parent::__construct();

        $this->options = $this->db->get('myseo_options')->row();
    }

    // get top pages
    public function top_pages()
    {
        $pages_raw = $this->db
            ->select('id, title')
            ->where('parent_id', 0)
            ->order_by('order')
            ->get('pages')
            ->result();

        $pages = array('0' => lang('myseo:pages:filters:select_all'));

        // make array for form_dropdown
        foreach ($pages_raw as $page)
        {
            $pages[$page->id] = $page->title;
        }

        return $pages;
    }

    // creates temporary index of all pages
    public function get_full_index($page_id, $hash, $index = array())
    {
        $children = $this->db
            ->select('id')
            ->where('parent_id', $page_id)
            ->order_by('order')
            ->get('pages')
            ->result();

        foreach ($children as $page)
        {
            $index[] = array(
                'item_id' => $page->id,
                'hash' => $hash
            );

            $children_count = $this->db->where('parent_id', $page_id)->from('pages')->count_all_results();

            if ($children_count != 0)
            {
                $index = $this->get_full_index($page->id, $hash, $index);
            }
        }

        return $index;
    }

    // create temporary filtered index of pages for display
    public function create_index($hash)
    {
        $filters = $this->myseo_filters_m->get_type('pages');

        // add top page to index, if not 0
        if ($filters->top_page != 0)
        {
            $this->db->insert('myseo_index', array(
                'item_id' => $filters->top_page,
                'hash' => $hash
            ));
        }

        // create full index, start with top_page from filters
        $index = $this->get_full_index($filters->top_page, $hash);

        if ( ! empty($index))
        {
            $this->db->insert_batch('myseo_index', $index);
        }

        unset($index);

        // get filtered index
        if ($filters->hide_drafts)
        {
            $this->db->where('pages.status', 'live');
        }

        if ($filters->by_title)
        {
            $this->db->like('pages.title', $filters->by_title);
        }

        if ($filters->by_uri)
        {
            $this->db->like('pages.uri', $filters->by_uri);
        }

        $index = $this->db
            ->select('myseo_index.item_id, myseo_index.hash')
            ->where('myseo_index.hash', $hash)
            ->order_by('myseo_index.id')
            ->join('pages', 'pages.id=myseo_index.item_id', 'left')
            ->get('myseo_index')
            ->result();

        // delete full index
        $this->db->delete('myseo_index', array('hash' => $hash));

        // create filtered index
        if ( ! empty($index))
        {
            $this->db->insert_batch('myseo_index', $index);
        }

        return $this->db->where('hash', $hash)->from('myseo_index')->count_all_results();
    }

    // gets all pages
    public function get_pages($hash, $offset)
    {
        $pages = $this->db
            ->select($this->select_fields)
            ->where('myseo_index.hash', $hash)
            ->order_by('myseo_index.id')
            ->join('pages', 'pages.id=myseo_index.item_id', 'left')
            ->get('myseo_index', $this->options->pagination_limit, $offset)
            ->result();

        // get actual keywords from pyro logic
        for ($i = 0;$i < count($pages);$i++)
        {
            $pages[$i]->meta_keywords = Keywords::get_string($pages[$i]->meta_keywords);
        }

        return $pages;
    }

    // gets list of pages for display
    public function get_list($offset)
    {
        $hash = md5(time() + microtime());

        $index_count = $this->create_index($hash);

        $pages = $this->get_pages($hash, $offset);

        $this->db->delete('myseo_index', array('hash' => $hash));

        return array($pages, $index_count);
    }
}