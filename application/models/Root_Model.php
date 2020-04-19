<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Root_Model extends CI_Model
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->timestamp = date('Y-m-d H:i:s');
        $this->admin_id = $this->session->userdata('admin_id');
        $this->dist_id = $this->session->userdata('dis_id');
    }
      public function published_root_info($id) {
//           echo '<pre>';
//        print_r($id);
//        exit();
        $this->db->set('is_active', 1);
        $this->db->where('root_id', $id);
        return $this->db->update('root_info');
    }

    public function unpublished_root_info($id) {

        $this->db->set('is_active', 0);
        $this->db->where('root_id', $id);
        return $this->db->update('root_info');
    }


}
?>

