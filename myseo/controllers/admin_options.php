<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Admin_options extends Admin_Controller
{
    protected $section = 'options';

    public function __construct()
    {
        parent::__construct();

        $this->lang->load('myseo');
    }

    public function index()
    {
        $this->template
            ->set('options', $this->db->get('myseo_options')->row())
            ->build('admin/options');
    }

    public function update()
    {
        $options = array(
            'max_title_len' => (int)$this->input->post('max_title_len'),
            'max_desc_len' => (int)$this->input->post('max_desc_len'),
            'pagination_limit' => (int)$this->input->post('pagination_limit'),
            'auto_collapse' => (int)$this->input->post('auto_collapse')
        );

        $this->db->set($options)->update('myseo_options');

        $this->session->set_flashdata('success', lang('myseo:options:update'));

        redirect('admin/myseo/options', 'location');
    }
}