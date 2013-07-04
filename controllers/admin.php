<?php defined('BASEPATH') OR exit('No direct script accsess allowed');
/**
 * MySeo admin controller
 * 2013
 *
 * @package     myseo/core
 * @author      Tanel Tammik <keevitaja@gmail.com>
 * @version     master
 *
 */
class Admin extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->model('myseo_m');
    }

    public function index()
    {
        // check if settings variable exists, if not create
        $settings = $this->myseo_m->get_settings();

        if (empty($settings))
        {
            $settings = array(
                'hide_drafts' => 1,
                'top_page' => 0,
                'filter_by_title' => '',
                'max_title_len' => 69,
                'max_desc_len' => 156
            );

            $this->myseo_m->set_settings($settings);
        }

        $this->template->settings = $settings;
        $this->template->top_pages = $this->myseo_m->get_top_pages();

        // get all pages with meta information and pass to view
        $this->template->pages = $this->myseo_m->get_all_pages($settings['top_page']);

        $this->template
            ->append_js('module::jquery.simplyCountable.js')
            ->append_js('module::myseo.js')
            ->build('admin/index');
    }

    // updates pages meta information
    public function update_page()
    {
        // type casting everything, no validation is needed
        $id = (int)$this->input->post('id');

        $data = array(
            'meta_title' => (string)$this->input->post('title'),
            'meta_keywords' => Keywords::process((string)$this->input->post('keywords')),
            'meta_description' => (string)$this->input->post('description'),
            'meta_robots_no_index' => ($this->input->post('robots_no_index')) ? 1 : 0,
            'meta_robots_no_follow' => ($this->input->post('robots_no_follow')) ? 1 : 0
        );

        $page = $this->db->select('meta_keywords')->where('id', $id)->get('pages')->row_array();

        // update
        $result = $this->db->where('id', $id)->update('pages', $data);

        if ($result)
        {
            $msg = '<span style="color: green">Success</span>';

            // delete old keywords_applied entry
            $this->db->where('hash', $page['meta_keywords'])->delete('keywords_applied');
        }
        else
        {
            $msg = '<span style="color: red">SQL Error</span>';
        }

        // determine request type and return
        if ($this->input->is_ajax_request())
        {
            // return result for ajax
            echo $msg;
        }
        else
        {
            $this->session->set_flashdata('meta_update_status', $msg);
            redirect('admin/myseo');
        }
    }

    // update settings in variables
    public function update_settings()
    {
        // delete old entry
        $this->db->where('name', 'myseo_settings')->delete('variables');

        $settings = array(
            'hide_drafts' => ($this->input->post('hide_drafts')) ? 1 : 0,
            'top_page' => (int)$this->input->post('top_page'),
            'filter_by_title' => trim((string)$this->input->post('filter_by_title')),
            'max_title_len' => (int)$this->input->post('max_title_len'),
            'max_desc_len' => (int)$this->input->post('max_desc_len')
        );

        $result = $this->myseo_m->set_settings($settings);

        // determine request type and return
        if ($result)
        {
            $msg = '<span style="color: green">Success</span>';
        }
        else
        {
            $msg = '<span style="color: red">SQL Error</span>';
        }

        $this->session->set_flashdata('settings_update_status', $msg);
        redirect('admin/myseo');
    }
}