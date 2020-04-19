<?php

/**
 * Created by PhpStorm.
 * User: Nahid
 * Date: 3/25/2019
 * Time: 9:36 AM
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class ProductPackageController extends CI_Controller
{

    private $timestamp;
    public $admin_id;
    public $dist_id;
    public $mainTemplate;

    public $project;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Common_model');
        $this->load->model('Finane_Model');
        $this->load->model('Inventory_Model');
        $this->load->model('Sales_Model');
        $this->load->model('Dashboard_Model');
        $thisdis = $this->session->userdata('dis_id');
        $admin_id = $this->session->userdata('admin_id');
        if (empty($thisdis) || empty($admin_id)) {
            redirect(site_url('DistributorDashboard'));
        }
        $this->timestamp = date('Y-m-d H:i:s');
        $this->admin_id = $this->session->userdata('admin_id');
        $this->dist_id = $this->session->userdata('dis_id');
        $this->mainTemplate = 'distributor/masterTemplate';

        $this->project = $this->session->userdata('project');
        $this->db_hostname = $this->session->userdata('db_hostname');
        $this->db_username = $this->session->userdata('db_username');
        $this->db_password = $this->session->userdata('db_password');
        $this->db_name = $this->session->userdata('db_name');
        $this->db->close();
        $config_app = switch_db_dinamico($this->db_username, $this->db_password, $this->db_name);
        $this->db = $this->load->database($config_app, TRUE);
    }

    public function product_package_list()
    {
        //productPackageAdd
        /*page navbar details*/
        $data['title'] = get_phrase('Product Package List');
        $data['page_type']=get_phrase($this->page_type);
        $data['link_page_name']=get_phrase('Product Package Add');
        $data['link_page_url']=$this->project.'/productPackageAdd';
        $data['link_icon']="<i class='fa fa-plus'></i>";
        /*page navbar details*/

        if (isPostBack()) {

        }
        $data['pageName'] = 'product_package_list';
        $data['mainContent'] = $this->load->view('distributor/inventory/product_package/product_package_list', $data, true);
        $this->load->view($this->mainTemplate, $data);
    }

    public function product_package_add()
    {
        /*page navbar details*/
        $data['title'] = get_phrase('Product Package Add');
        $data['page_type']=get_phrase($this->page_type);
        $data['link_page_name']=get_phrase('Product Package List');
        $data['link_page_url']=$this->project.'/productPackageList';
        $data['link_icon']="<i class='fa fa-list'></i>";
        /*page navbar details*/

        if (isPostBack()) {
            $this->form_validation->set_rules('package_name', 'Package Name', 'required');
            $this->form_validation->set_rules('product_id[]', 'Product ', 'required');
            if ($this->form_validation->run() == FALSE) {
                exception("Required field can't be empty.");
                redirect(site_url($this->project.'/productPackageAdd'));
            } else {

                $this->db->trans_start();
                $productOrgId = $this->db->where('dist_id', $this->dist_id)->or_where('dist_id', 1)->count_all_results('package') + 1;
                $dataSave['package_code'] = "PACID" . date('y') . date('m') . str_pad($productOrgId, 4, "0", STR_PAD_LEFT);

                $dataSave['package_name'] = $this->input->post('package_name');
                $dataSave['description'] = $this->input->post('description');
                $dataSave['dist_id'] = $this->dist_id;
                $dataSave['insert_by'] = $this->admin_id;
                $dataSave['insert_date'] = $this->timestamp;
                $dataSave['company_id'] = 0;
                $dataSave['branch_id'] = 0;

                $product_id = $this->input->post('product_id');
                $allProduct = array();
                $this->db->insert('package', $dataSave);
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
                    redirect(site_url($this->project.'/productPackageAdd/'));
                endif;
            }
        }
        $productOrgId = $this->db->where('dist_id', $this->dist_id)->or_where('dist_id', 1)->count_all_results('package') + 1;
        $data['packageid'] = "PACID" . date('y') . date('m') . str_pad($productOrgId, 4, "0", STR_PAD_LEFT);
        $data['productList'] = $this->Common_model->getPublicProductList($this->dist_id);

        $data['mainContent'] = $this->load->view('distributor/inventory/product_package/product_package_add', $data, true);
        $this->load->view($this->mainTemplate, $data);
    }

    public function product_package_edit($package_id)
    {

        if (isPostBack()) {
            $this->form_validation->set_rules('package_name', 'Package Name', 'required');
            $this->form_validation->set_rules('product_id[]', 'Product ', 'required');
            $this->form_validation->set_rules('package_id', 'Package ', 'required');

            //echo '<pre>';
            //print_r($_POST);
            //exit;


            if ($this->form_validation->run() == FALSE) {
                exception("Required field can't be empty.");
                redirect(site_url($this->project.'/productPackageAdd'));
            } else {

                $this->db->trans_start();

                $data['package_name'] = $this->input->post('package_name');
                $data['description'] = $this->input->post('description');
                $data['package_id'] = $this->input->post('package_id');
                $data['dist_id'] = $this->dist_id;
                $data['update_by'] = $this->admin_id;
                $data['update_date'] = $this->timestamp;

                $this->db->where('package_id', $package_id)
                    ->where('dist_id ', $this->dist_id)
                    ->update('package', $data);


                $product_id = $this->input->post('product_id');
                $allProductOld = array();
                $allProduct = array();
                $dataDetails = array();
                $dataDelete['is_active'] = 'N';
                $dataDelete['is_delete'] = 'Y';
                $this->db->where('package_id', $package_id)
                    ->where('dist_id ', $this->dist_id)
                    ->update('package_products', $dataDelete);

                foreach ($product_id as $key => $value):
                    unset($dataDetailsOld);
                    unset($dataDetails);
                    if (isset($_POST['package_products_id_' . $this->input->post('product_id')[$key]])) {
                        $dataDetailsOld['product_id'] = $this->input->post('product_id')[$key];
                        $dataDetailsOld['package_products_id'] = $_POST['package_products_id_' . $this->input->post('product_id')[$key]];
                        $dataDetailsOld['package_id'] = $package_id;

                        $dataDetailsOld['update_by'] = $this->admin_id;
                        $dataDetailsOld['update_date'] = $this->timestamp;

                        $dataDetailsOld['is_active'] = 'Y';
                        $dataDetailsOld['is_delete'] = 'N';
                        $allProductOld[] = $dataDetailsOld;
                    } else {

                        $dataDetails['product_id'] = $this->input->post('product_id')[$key];
                        $dataDetails['package_id'] = $package_id;
                        $dataDetails['dist_id'] = $this->dist_id;
                        $dataDetails['insert_by'] = $this->admin_id;
                        $dataDetails['insert_date'] = $this->timestamp;
                        $dataDetails['update_by'] = $this->admin_id;
                        $dataDetails['update_date'] = $this->timestamp;
                        $dataDetails['company_id'] = 0;
                        $dataDetails['branch_id'] = 0;
                        $dataDetails['is_active'] = 'Y';
                        $dataDetails['is_delete'] = 'N';
                        $allProduct[] = $dataDetails;
                    }

                endforeach;
                if (!empty($allProduct)) {
                    $this->db->insert_batch('package_products', $allProduct);
                }

                $this->db->update_batch('package_products', $allProductOld, 'package_products_id');
                $this->db->trans_complete();

                if ($this->db->trans_status() === FALSE):

                    $this->session->set_flashdata('message', "Product Package Can't Save.");
                    redirect(site_url($this->project.'/productPackageEdit/' . $package_id));
                else:

                    $this->session->set_flashdata('success', "Product Package Save successfully nahid.");

                    redirect(site_url($this->project.'/productPackageList'));
                endif;
            }
        }


//    $joins[0]['table'] = 'reference';
//    $joins[0]['conditionition'] = 'reference.reference_id=customer.reference_id';
//    $joins[0]['jointype'] = 'left';
//
//    $condition['customer.customer_id'] = $customerid;
//    $condition['customer.dist_id'] = $this->dist_id;
//    $select_fields = 'customer.customerPhone,customer.customerAddress,customer.division,customer.thanna,customer.district,reference.reference_id,reference.refCode,reference.referenceName,reference.referencePhone';
//
//
//    $customer_info = $this->Common_model->join_select_one_row_array('customer', $select_fields, $joins, $condition);


        $data['productList'] = $this->Common_model->getPublicProductList($this->dist_id);

        $select_fields = 'package.package_name,package.package_code,package.package_id,package_products.package_products_id,package_products.product_id,
                        product.productName,product.brand_id,product.category_id,
                        productcategory.title,
                        brand.brandName';
        $joins[0]['table'] = 'package_products';
        $joins[0]['conditionition'] = 'package_products.package_id=package.package_id';
        $joins[0]['jointype'] = 'left';

        $joins[1]['table'] = 'product';
        $joins[1]['conditionition'] = 'package_products.product_id=product.product_id';
        $joins[1]['jointype'] = 'left';

        $joins[2]['table'] = 'productcategory';
        $joins[2]['conditionition'] = 'productcategory.category_id=product.category_id';
        $joins[2]['jointype'] = 'left';


        $joins[3]['table'] = 'brand';
        $joins[3]['conditionition'] = 'brand.brandId=product.brand_id ';
        $joins[3]['jointype'] = 'left';

        $condition['package.package_id'] = $package_id;
        //$condition['package.dist_id'] = $this->dist_id;
        $condition['package.is_active'] = 'Y';
        $condition['package.is_delete'] = 'N';
        $condition['package_products.is_active'] = 'Y';
        $condition['package_products.is_delete'] = 'N';

        $data['package_details'] = $this->Common_model->join_select_all_row_obj('package', $select_fields, $joins, $condition, 'Y');
        //echo '<pre>';
        //echo $this->db->last_query();
        //print_r($data['package_details']);
        //exit;

        $data['title'] = get_phrase('Product Package Edit');
        $data['page_type']=get_phrase($this->page_type);
        $data['link_page_name']=get_phrase('Product Package Add');
        $data['link_page_url']=$this->project.'/productPackageAdd';
        $data['link_icon']="<i class='fa fa-plus'></i>";


        $data['pageName'] = 'product_package_add';
        $data['mainContent'] = $this->load->view('distributor/inventory/product_package/product_package_edit', $data, true);
        $this->load->view($this->mainTemplate, $data);
    }

    public function product_package_view($package_id)
    {


//    $joins[0]['table'] = 'reference';
//    $joins[0]['conditionition'] = 'reference.reference_id=customer.reference_id';
//    $joins[0]['jointype'] = 'left';
//
//    $condition['customer.customer_id'] = $customerid;
//    $condition['customer.dist_id'] = $this->dist_id;
//    $select_fields = 'customer.customerPhone,customer.customerAddress,customer.division,customer.thanna,customer.district,reference.reference_id,reference.refCode,reference.referenceName,reference.referencePhone';
//
//
//    $customer_info = $this->Common_model->join_select_one_row_array('customer', $select_fields, $joins, $condition);


        $data['productList'] = $this->Common_model->getPublicProductList($this->dist_id);

        $select_fields = 'package.package_name,package.package_code,package.package_id,package_products.package_products_id,package_products.product_id,
                        product.productName,product.brand_id,product.category_id,
                        productcategory.title,
                        brand.brandName';
        $joins[0]['table'] = 'package_products';
        $joins[0]['conditionition'] = 'package_products.package_id=package.package_id';
        $joins[0]['jointype'] = 'left';

        $joins[1]['table'] = 'product';
        $joins[1]['conditionition'] = 'package_products.product_id=product.product_id';
        $joins[1]['jointype'] = 'left';

        $joins[2]['table'] = 'productcategory';
        $joins[2]['conditionition'] = 'productcategory.category_id=product.category_id';
        $joins[2]['jointype'] = 'left';


        $joins[3]['table'] = 'brand';
        $joins[3]['conditionition'] = 'brand.brandId=product.brand_id ';
        $joins[3]['jointype'] = 'left';

        $condition['package.package_id'] = $package_id;
        $condition['package.dist_id'] = $this->dist_id;
        $condition['package.is_active'] = 'Y';
        $condition['package.is_delete'] = 'N';
        $condition['package_products.is_active'] = 'Y';
        $condition['package_products.is_delete'] = 'N';

        $data['package_details'] = $this->Common_model->join_select_all_row_obj('package', $select_fields, $joins, $condition, 'Y');
        //echo '<pre>';
        //echo $this->db->last_query();
        //print_r($data['package_details']);
        //exit;
        $data['pageName'] = 'product_package_add';
        $data['mainContent'] = $this->load->view('distributor/inventory/product_package/product_package_view', $data, true);
        $this->load->view($this->mainTemplate, $data);
    }

    function checkDuplicateProductPackage()
    {
        $package_id = $this->input->post('package_id');
        $package_name = $this->input->post('package_name');
        if (!empty($package_id)):
            $productpackageExits = $this->Common_model->checkDuplicateProductPackage($this->dist_id, $package_name, $package_id);
        else:
            $productpackageExits = $this->Common_model->checkDuplicateProductPackage($this->dist_id, $package_name);
        endif;
        if (!empty($productpackageExits)) {
            echo 1;
        }
    }

    function product_package_delete($product_package_id)
    {

        $data['is_active'] = 'N';
        $data['is_delete'] = 'Y';
        $data['update_by'] = $this->admin_id;
        $data['update_date'] = $this->timestamp;
        $result = $this->Common_model->update_data_by_dist_id('package', $data, 'package_id', $product_package_id, $this->dist_id);
        if ($result != 0) {
            message("Your data successully deleted from database.");
        } else {
            exception("This Product Type can't be deleted.already have a transaction!");
        }
        redirect(site_url('productPackageList'));
    }

    function productPackageStatusChange()
    {
        $package_id = $this->input->post('package_id');
        $data['is_active'] = $this->input->post('status') == 1 ? 'Y' : 'N';
        $data['update_by'] = $this->admin_id;
        $data['update_date'] = $this->timestamp;
        $this->Common_model->update_data('package', $data, 'package_id', $package_id);
        message("Product Status successfully change.");
        echo 1;
    }

}
