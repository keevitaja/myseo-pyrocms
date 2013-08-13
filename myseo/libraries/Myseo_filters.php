<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Myseo_filters
{
    private $ci;
    private $filters;

    public function __construct()
    {
        $this->ci =& get_instance();
    }

    // get data for the filters view
    public function get($type)
    {
        $this->filters = $this->ci->myseo_filters_m->get_type($type);

        $filters_data = $this->ci->config->item('myseo_filters');
        $filters_data = $filters_data[$type];
        $filters = array();

        foreach ($filters_data as $name => $data)
        {
            $type = $data['type'];
            $method = (isset($data['method'])) ? $data['method'] : false;

            $filters[] = array(
                'name' => $name,
                'title' => $data['title'],
                'title_long' => $data['title_long'],
                'input_field' => $this->$type($name, $method)
            );
        }

        return $filters;
    }

    public function checkbox($name)
    {
        $checkbox = form_checkbox(array(
            'name' => $name,
            'value' => 1,
            'checked' => $this->filters->$name
        ));

        return '<label>' . $checkbox . '</label>';
    }

    public function input($name)
    {
        $input = form_input(array(
            'name' => $name,
            'value' => $this->filters->$name
        ));

        return $input;
    }

    public function dropdown_from_model($name, $method)
    {
        $dropdown = form_dropdown(array(
            'name' => $name,
            'options' => $this->ci->myseo_m->$method(),
            'selected' => $this->filters->$name,
            'extra' => ''
        ));

        return $dropdown;
    }
}