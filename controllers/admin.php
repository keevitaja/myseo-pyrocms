<?php defined('BASEPATH') OR exit('No direct script accsess allowed');

/**
 * Myseo - admin controller
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

class Admin extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->model('myseo_m');
        $this->lang->load('myseo');
    }

    // main controller
    public function index($offset = 0)
    {
        $options = $this->myseo_m->get_options();
        $update_status = $this->session->flashdata('flash_filters_update_status');
        list($pages, $count) = $this->myseo_m->get_pages($options['filter_by_top_page'], $offset);

        $this->template->filters_update_status = ($update_status) ? $update_status : '&nbsp';

        $this->load->library('pagination');

        $this->pagination->initialize(array(
            'base_url' => site_url('admin/myseo'),
            'total_rows' => $count,
            'per_page' => $options['pagination_limit']
        ));


        $this->template->pagination = $this->pagination->create_links();

        $this->template
            ->set('myseo_options', $options)
            ->set('top_pages', $this->myseo_m->get_top_pages())
            ->set('pages', $pages)
            ->append_css('module::myseo.css')
            ->append_js('module::jquery.simplyCountable.js')
            ->append_js('module::myseo.js')
            ->build('admin/index');
    }

    // updates filters
    public function filters_update()
    {
        $filters = array(
            'hide_drafts' => (int)$this->input->post('hide_drafts'),
            'filter_by_top_page' => (int)$this->input->post('filter_by_top_page'),
            'filter_by_title' => (string)$this->input->post('filter_by_title'),
            'filter_by_uri' => (string)$this->input->post('filter_by_uri'),
        );

        $result = $this->myseo_m->update_options($filters);

        if ($result)
        {
            $update_status = '<span class="myseo-success">Success</span>';
        }
        else
        {
            $update_status = '<span class="myseo-error">SQL Error</span>';
        }

        $this->session->set_flashdata('flash_filters_update_status', $update_status);
        redirect('admin/myseo', 'location');
    }

    // options controller
    public function options()
    {
        $update_status = $this->session->flashdata('flash_options_update_status');
        $this->template->options_update_status = ($update_status) ? $update_status : '&nbsp';

        $this->template
            ->set('myseo_options', $this->myseo_m->get_options())
            ->append_js('module::myseo.js')
            ->append_css('module::myseo.css')
            ->build('admin/options');
    }

    // update options
    public function options_update()
    {
        $options = array(
            'max_title_len' => (int)$this->input->post('max_title_len'),
            'max_desc_len' => (int)$this->input->post('max_desc_len'),
            'pagination_limit' => (int)$this->input->post('pagination_limit'),
            'auto_hide_rows' => (int)$this->input->post('auto_hide_rows'),
            'auto_hide_filters' => (int)$this->input->post('auto_hide_filters'),
        );

        $result = $this->myseo_m->update_options($options);

        if ($result)
        {
            $update_status = '<span class="myseo-success">Success</span>';
        }
        else
        {
            $update_status = '<span class="myseo-error">SQL Error</span>';
        }

        $this->session->set_flashdata('flash_options_update_status', $update_status);
        redirect('admin/myseo/options', 'location');
    }

    // updates page
    public function page_update()
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

        // store page data for old keyword hash for later deletion
        $page = $this->db->select('meta_keywords')->where('id', $id)->get('pages')->row_array();

        // update
        $result = $this->db->where('id', $id)->update('pages', $data);

        if ($result)
        {
            $update_status = '<span class="myseo-success">' . lang('myseo:request_msg_success') . '</span>';

            // delete old keywords_applied entry
            $this->db->where('hash', $page['meta_keywords'])->delete('keywords_applied');
        }
        else
        {
            $update_status = '<span class="myseo-error">' . lang('myseo:request_msg_sql_error') . '</span>';
        }

        // determine request type and return
        if ($this->input->is_ajax_request())
        {
            echo $update_status;
        }
        else
        {
            $this->session->set_flashdata('flash_page_update_status', array($id => $update_status));
            redirect('admin/myseo#ID' . $id, 'location');
        }
    }
}