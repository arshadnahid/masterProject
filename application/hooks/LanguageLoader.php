<?php
/**
 * Created by PhpStorm.
 * User: AEL-DEV
 * Date: 8/24/2019
 * Time: 3:33 PM
 */
class LanguageLoader
{
    function initialize() {
        $ci =& get_instance();
        $ci->load->helper('language');
        $siteLang = $ci->session->userdata('site_lang');
        if ($siteLang) {
            $ci->lang->load('content',$siteLang);
        } else {
            $ci->lang->load('content','english');
        }
    }
}