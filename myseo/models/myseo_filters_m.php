<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Myseo_filters_m extends MY_Model
{
    public function get_type($type)
    {
        $filters = $this->db->select('name, value')->where('type', $type)->get('myseo_filters')->result();

        $obj = new stdClass();

        foreach ($filters as $filter)
        {
            $key = $filter->name;

            $obj->$key = $filter->value;
        }

        return $obj;
    }
}