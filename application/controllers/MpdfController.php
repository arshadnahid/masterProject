<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MpdfController extends CI_Controller {

    public function index(){
        $data['kkkkk']='Nah';
        $this->load->view('welcome_messagee', $data, true);

        // Get output html
        $html = $this->output->get_output();

        // Load pdf library
        $this->load->library('pdf');

        // Load HTML content
        $this->dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation
        $this->dompdf->setPaper('A4', 'portraint');

        // Render the HTML as PDF
        $this->dompdf->render();

        // Output the generated PDF (1 = download and 0 = preview)
        $this->dompdf->stream("welcome.pdf", array("Attachment"=>0));
    }

}