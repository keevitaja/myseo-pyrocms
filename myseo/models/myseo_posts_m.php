<?php defined('BASEPATH') OR exit('No direct script access allowed');

// consider model as a driver for module or a stream

class Myseo_posts_m extends MY_Model
{
    // select
    private $select_fields = '
        myseo_posts_meta.id,
        blog.title as the_title,
        blog.slug as the_uri,
        myseo_posts_meta.title,
        myseo_posts_meta.keywords,
        myseo_posts_meta.description,
        myseo_posts_meta.no_index,
        myseo_posts_meta.no_follow
    ';

    private $options;

    public function __construct()
    {
        parent::__construct();

        $this->options = $this->db->get('myseo_options')->row();
    }

    // get categories
    public function categories()
    {
        $cats_raw = $this->db->select('id, title')->order_by('title')->get('blog_categories')->result();

        $cats = array('0' => lang('myseo:posts:filters:select_all'));

        // make array for form_dropdown
        foreach ($cats_raw as $cat)
        {
            $cats[$cat->id] = $cat->title;
        }

        return $cats;
    }

    // sync meta table with blog table
    public function sync_tables()
    {
        // get all blog posts
        $posts = $this->db->select('id')->get('blog')->result();

        foreach ($posts as $post)
        {
            // test in meta
            $count = $this->db->where('post_id', $post->id)->from('myseo_posts_meta')->count_all_results();

            if ($count == 0)
            {
                $this->db->insert('myseo_posts_meta', array('post_id' => $post->id));
            }
        }
    }

    // create tmp index of posts
    public function create_index($hash)
    {
        // filter posts
        $filters = $this->myseo_filters_m->get_type('posts');

        if ($filters->hide_drafts)
        {
            $this->db->where('blog.status', 'live');
        }

        if ($filters->category)
        {
            $this->db->where('blog.category_id', $filters->category);
        }

        if ($filters->by_title)
        {
            $this->db->like('blog.title', $filters->by_title);
        }

        if ($filters->by_slug)
        {
            $this->db->like('blog.slug', $filters->by_slug);
        }

        $posts = $this->db
            ->select('blog.id')
            ->join('myseo_posts_meta', 'blog.id=myseo_posts_meta.post_id', 'left')
            ->order_by('blog.created', 'desc')
            ->get('blog')->result();

        $index = array();

        foreach ($posts as $post)
        {
            $index[] = array(
                'hash' => $hash,
                'item_id' => $post->id
            );
        }

        // create filtered index
        if ( ! empty($index))
        {
            $this->db->insert_batch('myseo_index', $index);
        }

        return $this->db->where('hash', $hash)->from('myseo_index')->count_all_results();
    }

    // get posts
    public function get_posts($hash, $offset)
    {
        $posts = $this->db
            ->select($this->select_fields)
            ->where('myseo_index.hash', $hash)
            ->join('myseo_posts_meta', 'myseo_index.item_id=myseo_posts_meta.post_id', 'left')
            ->join('blog', 'blog.id=myseo_posts_meta.post_id', 'left')
            ->order_by('myseo_index.id')
            ->get('myseo_index', $this->options->pagination_limit, $offset)->result();

        // get actual keywords from pyro logic
        for ($i = 0;$i < count($posts);$i++)
        {
            $posts[$i]->keywords = Keywords::get_string($posts[$i]->keywords);
        }

        return $posts;
    }

    // gets list og blog posts
    public function get_list($offset)
    {
        // add all posts to metadata table
        $this->sync_tables();

        $hash = md5(time() + microtime());

        $index_count = $this->create_index($hash);

        // get post meta
        $posts = $this->get_posts($hash, $offset);

        $this->db->delete('myseo_index', array('hash' => $hash));

        return array($posts, $index_count);
    }
}