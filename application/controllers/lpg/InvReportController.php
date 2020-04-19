<?php
/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 7/13/2019
 * Time: 12:42 PM
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class InvReportController extends CI_Controller
{


    private $timestamp;
    private $admin_id;
    public $dist_id;
    public $invoice_id;
    public $page_type;
    public $folder;
    public $folderSub;
    public $link_icon_add;
    public $link_icon_list;

    public $project;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Common_model');
        $this->load->model('Finane_Model');
        $this->load->model('Inventory_Model');
        $this->load->model('Sales_Model');
        $this->load->model('Purchases_Model');
        //$this->load->model('Datatable');
        $this->timestamp = date('Y-m-d H:i:s');
        $this->admin_id = $this->session->userdata('admin_id');
        $this->dist_id = $this->session->userdata('dis_id');
        if (empty($this->admin_id) || empty($this->dist_id)) {
            redirect(site_url());
        }
        $this->invoice_type = 1;
        $this->page_type = 'inventory';
        $this->folder = 'distributor/masterTemplate';
        $this->folderSub = 'distributor/inventory/report/';
        $this->link_icon_add = "<i class='ace-icon fa fa-plus'></i>";
        $this->link_icon_list = "<i class='ace-icon fa fa-list'></i>";

        $this->project = $this->session->userdata('project');
        $this->db_hostname = $this->session->userdata('db_hostname');
        $this->db_username = $this->session->userdata('db_username');
        $this->db_password = $this->session->userdata('db_password');
        $this->db_name = $this->session->userdata('db_name');
        $this->db->close();
        $config_app = switch_db_dinamico($this->db_username, $this->db_password, $this->db_name);
        $this->db = $this->load->database($config_app, TRUE);
    }
    public  function  cylinder_stock_group_report__al_old(){

        if (isPostBack()) {

            $brandId = $this->input->post('brandId');
            $branch_id = $this->input->post('branch_id');
            $start_date = date('Y-m-d', strtotime($this->input->post('start_date')));
            $end_date = date('Y-m-d', strtotime($this->input->post('end_date')));
            $data['allStock'] = $this->Inventory_Model->cylinder_stock_group_report($start_date, $end_date,  $brandId,$branch_id);
            /*echo"<pre>";
            echo $this->db->last_query();
            print_r(count($data['allStock']));exit;*/

            if ($this->input->post('is_print') == 1) {
                $footer = '';
                $data['companyInfo'] = $companyInfo = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
                $footer1 = '<table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;"><tr>
<td width="33%"><span style="font-weight: bold; font-style: italic;">{DATE j-m-Y}</span></td>
<td width="33%" align="center" style="font-weight: bold; font-style: italic;">{PAGENO}/{nbpg}</td>

<td width="33%" style="text-align: right; ">My document</td>

</tr></table>';


                $output_type = 'L';
                $header = '<table class="table table-responsive">
                    <tr>
                    <td style="text-align:center;"><h3>' . $companyInfo->companyName . '</h3><span>' . $companyInfo->address . '</span><br><strong>' . get_phrase('Phone') . ': </strong>' . $companyInfo->phone . '<br><strong>' . get_phrase('Email') . ': </strong>' . $companyInfo->email . '<br><strong>' . get_phrase('Website') . ': </strong>' . $companyInfo->website . '<br><strong>' . 'General Ledger Report
' . '</strong><strong> ' . get_phrase('') . ' :</strong>From ' . $start_date . ' To ' . $end_date . '</td></tr></table>';
                $this->load->library('tec_mpdf', '', 'pdf');
                //$header="This is hadder";
                $content=$this->load->view($this->folderSub.'cylinder_stock_group_report', $data, true);
                $this->pdf->generate($content, $name = 'download.pdf', $output_type, $footer, $margin_bottom = null, $header, $margin_top = '40', $orientation = 'l');

            }



        }
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);



        /*page navbar details*/
        $data['title']= get_phrase('Cylinder_Group_Report');
        $data['page_type']=$this->page_type;
        $data['link_page_name']='';
        $data['link_page_url']='';
        $data['link_icon']= '';
        /*page navbar details*/

        $data['brandList'] = $this->Common_model->getPublicBrand($this->dist_id);

        $data['mainContent'] = $this->load->view($this->folderSub.'cylinder_stock_group_report', $data, true);
        $this->load->view($this->folder, $data);

    }


        public function cylinder_stock_group_report()
    {

        if (isPostBack()) {

            $productCatagory = $this->input->post('category_id');
            $productBrand = $this->input->post('brandId');
            $productId = $this->input->post('productId');
            $startDate = date('Y-m-d', strtotime($this->input->post('start_date')));
            $endDate = date('Y-m-d', strtotime($this->input->post('end_date')));
            $data['stock']=$this->Inventory_Model->stock_report($productCatagory,$productBrand,$productId,$startDate,$endDate);
            $data['stockEmptyCylinder']=$this->Inventory_Model->get_empty_cylinder_with_refill_with_out_refill($productCatagory,$productBrand,$productId,$startDate,$endDate);
/*echo '<pre>';
print_r($data['stockEmptyCylinder']);
exit;*/
        }
        $data['productCat'] = $this->Common_model->getPublicProductCat($this->dist_id);
        $data['productList'] = $this->Common_model->getPublicProductWithoutCat($this->dist_id);
        $data['brandList'] = $this->Common_model->getPublicBrand($this->dist_id);
        $data['title'] = get_phrase('cylinder_stock_group_report');
        $data['page_type'] = $this->page_type;
        $data['link_page_name'] = '';
        $data['link_page_url'] = '';
        $data['link_icon'] = "";
        /*page navbar details*/
        $data['companyInfo'] = $this->Common_model->get_single_data_by_single_column('system_config', 'dist_id', $this->dist_id);
        $data['mainContent'] = $this->load->view('distributor/inventory/report/cylinder_stock_group_report', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
        $this->output->enable_profiler(false);
    }

}