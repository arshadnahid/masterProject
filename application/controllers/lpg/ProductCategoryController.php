<?php
/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 7/7/2019
 * Time: 12:15 PM
 */


defined('BASEPATH') OR exit('No direct script access allowed');
class ProductCategoryController extends CI_Controller
{


    private $timestamp;
    private $admin_id;
    public $dist_id;
    public $invoice_id;
    public $page_type;
    public $folder;
    public $folderSub;

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
        $this->folderSub = 'distributor/inventory/productCat/';

        $this->project = $this->session->userdata('project');
        $this->db_hostname = $this->session->userdata('db_hostname');
        $this->db_username = $this->session->userdata('db_username');
        $this->db_password = $this->session->userdata('db_password');
        $this->db_name = $this->session->userdata('db_name');
        $this->db->close();
        $config_app = switch_db_dinamico($this->db_username, $this->db_password, $this->db_name);
        $this->db = $this->load->database($config_app, TRUE);
    }

    function productCatList() {
        $data['page_type']=get_phrase($this->page_type) ;
        /*page navbar details*/
        $data['title'] = get_phrase('Product Category');

        $data['link_page_name']=get_phrase('Product Category Add');
        $data['link_page_url']=$this->project.'/addProductCat';
        $data['link_icon']="<i class='fa fa-plus'></i>";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view($this->folderSub.'productCatList', $data, true);
        $this->load->view($this->folder, $data);
    }
    function addProductCat() {
        if (isPostBack()) {
            $this->form_validation->set_rules('title', 'Product Category', 'required');
            if ($this->form_validation->run() == FALSE) {
                $msg= $this->config->item("form_validation_message");
                $this->session->set_flashdata('error', $msg);
            } else {
                $this->db->trans_start();
                $data['title'] = $this->input->post('title');
                $data['dist_id'] = $this->dist_id;
                $data['updated_by'] = $this->admin_id;
                $insertID = $this->Common_model->insert_data('productcategory', $data);
                $this->db->trans_complete();


                if ($this->db->trans_status() === FALSE):
                    $msg= 'Product Category '.' '.$this->config->item("save_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project.'/addProductCat' ));
                else:
                    $msg= 'Product Category '.' '.$this->config->item("save_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project.'/addProductCat'));
                endif;
            }
        }
        $data['title'] = get_phrase('Product Category Add');
        /*page navbar details*/
        
        $data['page_type']=get_phrase($this->page_type);
        $data['link_page_name']=get_phrase('Product Category List');
        $data['link_page_url']=$this->project.'/productCatList';
        $data['link_icon']="<i class='fa fa-list'></i>";
        /*page navbar details*/
        $data['mainContent'] = $this->load->view($this->folderSub.'productCatAdd', $data, true);
        $this->load->view($this->folder, $data);
    }
    function updateProductCat($updated_id) {
        if (isPostBack()) {
            $this->form_validation->set_rules('title', 'Product Category', 'required');
            if ($this->form_validation->run() == FALSE) {
                $msg= 'Product Category '.' '.$this->config->item("form_validation_message");
                $this->session->set_flashdata('error', $msg);
                redirect(site_url($this->project.'/addProductCat'));
            } else {

                $this->db->trans_start();
                $data['title'] = $this->input->post('title');
                $data['dist_id'] = $this->dist_id;
                $data['updated_at'] = $this->timestamp;
                $data['updated_by'] = $this->admin_id;
                $updateID = $this->Common_model->update_data('productcategory', $data, 'category_id', $updated_id);
                $this->db->trans_complete();


                if ($this->db->trans_status() === FALSE):
                    $msg= 'Product Category '.' '.$this->config->item("update_error_message");
                    $this->session->set_flashdata('error', $msg);
                    redirect(site_url($this->project.'/productCatList/' ));
                else:
                    $msg= 'Product Category '.' '.$this->config->item("update_success_message");
                    $this->session->set_flashdata('success', $msg);
                    redirect(site_url($this->project.'/updateProductCat/' . $updateID));
                endif;
            }
        }
        $data['page_type']=$this->page_type ;
        $data['updateCatInfo'] = $this->Common_model->get_single_data_by_single_column('productcategory', 'category_id', $updated_id);
        $data['title'] = 'Update Product Category ';
        $data['mainContent'] = $this->load->view($this->folderSub.'updateProductCat', $data, true);
        $this->load->view($this->folder, $data);
    }

    function deleteProductCategory($deletedId) {
        $inventoryCondition = array(
            'dist_id' => $this->dist_id,
            'category_id' => $deletedId,
        );
        $exits = $this->Common_model->get_data_list_by_many_columns('product', $inventoryCondition);
        if (empty($exits)) {
            $condition = array(
                'dist_id' => $this->dist_id,
                'category_id' => $deletedId
            );
            $this->db->trans_start();
            $this->Common_model->delete_data_with_condition('productcategory', $condition);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE):
                $msg= 'Product Category '.' '.$this->config->item("delete_error_message");
                $this->session->set_flashdata('error', $msg);

            else:
                $msg= 'Product Category '.' '.$this->config->item("delete_success_message");
                $this->session->set_flashdata('success', $msg);

            endif;
            redirect(site_url($this->project.'/productCatList'));
        } else {
            $msg= "This Category can't be deleted.already have a product created by this category";
            $this->session->set_flashdata('error', $msg);

            redirect(site_url($this->project.'/productCatList'));
        }
    }
    public function product_package_add()
    {

        if (isPostBack()) {
            $this->form_validation->set_rules('package_name', 'Package Name', 'required');
            $this->form_validation->set_rules('product_id[]', 'Product ', 'required');
            if ($this->form_validation->run() == FALSE) {
                exception("Required field can't be empty.");
                redirect(site_url($this->project.'/productPackageAdd'));
            } else {

                $this->db->trans_start();
                $productOrgId = $this->db->where('dist_id', $this->dist_id)->or_where('dist_id', 1)->count_all_results('package') + 1;
                $data['package_code'] = "PACID" . date('y') . date('m') . str_pad($productOrgId, 4, "0", STR_PAD_LEFT);

                $data['package_name'] = $this->input->post('package_name');
                $data['description'] = $this->input->post('description');
                $data['dist_id'] = $this->dist_id;
                $data['insert_by'] = $this->admin_id;
                $data['insert_date'] = $this->timestamp;
                $data['company_id'] = 0;
                $data['branch_id'] = 0;

                $product_id = $this->input->post('product_id');
                $allProduct = array();
                $this->db->insert('package', $data);
                $package_id = $this->db->insert_id();

                foreach ($product_id as $key => $value):
                    unset($dataDetails);
                    $dataDetails['product_id'] = $this->input->post('product_id')[$key];
                    $dataDetails['package_id'] = $package_id;
                    $dataDetails['dist_id'] = $this->dist_id;
                    $dataDetails['insert_by'] = $this->admin_id;
                    $dataDetails['insert_date'] = $this->timestamp;
                    $dataDetails['company_id'] = 0;
                    $dataDetails['branch_id'] = 0;
                    $allProduct[] = $dataDetails;
                endforeach;

                $this->db->insert_batch('package_products', $allProduct);
                $this->db->trans_complete();

                if ($this->db->trans_status() === FALSE):
                    $this->session->set_flashdata('error', "Product Package Can't Save.");
                    //message("Product Package Can't Save.");
                    redirect(site_url($this->project.'/productPackageAdd/' . $package_id));
                else:
                    $this->session->set_flashdata('success', "Product Package Save successfully.");
                    //message("Product Package Save successfully.");
                    redirect(site_url($this->project.'/productPackageEdit/' . $package_id));
                endif;
            }
        }
        $productOrgId = $this->db->where('dist_id', $this->dist_id)->or_where('dist_id', 1)->count_all_results('package') + 1;
        $data['packageid'] = "PACID" . date('y') . date('m') . str_pad($productOrgId, 4, "0", STR_PAD_LEFT);
        $data['productList'] = $this->Common_model->getPublicProductList($this->dist_id);
        $data['mainContent'] = $this->load->view('distributor/inventory/product_package/product_package_add', $data, true);
        $this->load->view('distributor/masterTemplate', $data);
    }
    
    
}
