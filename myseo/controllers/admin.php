<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends Admin_Controller
{
    protected $section;

    private $type;
    private $fields;

    public function __construct()
    {
        // get type of which modules meta to edit
        $this->type = ($this->uri->uri_string() == 'admin/myseo') ? 'pages' : $this->uri->segment(3);

        // set module section
        $this->section = $this->type;

        parent::__construct();

        $this->lang->load('myseo');
        $this->config->load('myseo');
        $this->load->model('myseo_' . $this->type . '_m', 'myseo_m');
        $this->load->model('myseo_filters_m');

        // get table fields
        $fields = $this->config->item('myseo_fields');
        $this->fields = $fields[$this->type];
    }

    public function index($offset = 0)
    {
        // get list of pages, index count
        list($list, $item_count) = $this->myseo_m->get_list($offset);

        // get myseo options
        $options = $this->db->get('myseo_options')->row();

        // get filters data for view
        $this->load->library('myseo_filters');
        $filters = $this->myseo_filters->get($this->type);

        // init pagination
        $this->load->library('pagination');

        $this->pagination->initialize(array(
            'base_url' => site_url('admin/myseo/' . $this->type . '/index'),
            'total_rows' => $item_count,
            'per_page' => $options->pagination_limit,
            'uri_segment' => 5
        ));

        $this->template
            ->set('list', $list)
            ->set('pagination', $this->pagination->create_links())
            ->set('options', $options)
            ->set('filters', $filters)
            ->set('type', $this->type)
            ->set('fields', $this->fields)
            ->append_css('module::myseo.css')
            ->append_js('module::jquery.simplyCountable.js')
            ->append_js('module::myseo.js')
            ->build('admin/list');
    }

    public function update($id = 0)
    {
        // get table
        $table = $this->config->item('myseo_tables');

        $item = $this->db->where('id', $id)->get($table[$this->type])->row();;

        // check if page exists, this should not happen!
        if (empty($item))
        {
            show_error(lang('myseo:error:var'), 500);
        }

        $metadata = array(
            $this->fields['title'] => trim((string)$this->input->post('meta_title')),
            $this->fields['keywords'] => Keywords::process((string)$this->input->post('meta_keywords')),
            $this->fields['description'] => trim((string)$this->input->post('meta_description')),
            $this->fields['no_index'] => (int)$this->input->post('meta_robots_no_index'),
            $this->fields['no_follow'] => (int)$this->input->post('meta_robots_no_follow')
        );

        // update metadata info
        $this->db->where('id', $id)->update($table[$this->type], $metadata);

        // delete old keyword hash
        $keyword_field =  $this->fields['keywords'];
        $this->db->where('hash', $item->$keyword_field)->delete('keywords_applied');

        if ($this->input->is_ajax_request())
        {
            echo lang('myseo:' . $this->type . ':update:ajax');
        }
        else
        {
            $this->session->set_flashdata('success', lang('myseo:' . $this->type . ':update:post'));

            redirect('admin/myseo/' . $this->type, 'location');
        }
    }

    public function update_filters()
    {
        $filters = $this->config->item('myseo_filters');

        foreach (array_keys($filters[$this->type]) as $name)
        {
            $value = trim($this->input->post($name));

            $this->db
                ->set('value', $value)
                ->where(array(
                    'type' => $this->type,
                    'name' => $name
                ))
                ->update('myseo_filters');
        }

        $this->session->set_flashdata('success', lang('myseo:filters:update'));

        redirect('admin/myseo/' . $this->type, 'location');
    }
}