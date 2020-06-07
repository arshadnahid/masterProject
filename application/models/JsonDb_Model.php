<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class JsonDb_Model extends CI_Model
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->timestamp = date('Y-m-d H:i:s');
        $this->admin_id = $this->session->userdata('admin_id');
        $this->dist_id = $this->session->userdata('dis_id');
    }



    public  function sales_input_back_up($invoice_no,$invoice_save_data){
        $path="./jsonDB/".$this->project.'/'.date('Y-m-d');

        if($path) {
            if(!file_exists($path)) {

                mkdir($path, 0777, true);


            }
        }

        $filename=date('Y-m-d')."sales-invoice.JSON";
        if (!file_exists($path.'/'. $filename)){

            $array_data=array();
            $extra[$invoice_no] = $invoice_save_data;
            $extra[$invoice_no]['POST_DATA']= $_POST;
            $array_data[] = $extra;

            file_put_contents($path.'/'. $filename, json_encode($array_data), FILE_APPEND | LOCK_EX);


        }else{
            $current_data = file_get_contents($path.'/'. $filename);
            $array_data = json_decode($current_data, true);
            $extra[$invoice_no] = $invoice_save_data;
            $extra[$invoice_no]['POST_DATA']= $_POST;

            $array_data[] = $extra;
            $final_data = json_encode($array_data);
            file_put_contents($path.'/'. $filename, $final_data);

        }

    }


}