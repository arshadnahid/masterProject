<div id="wait" style="display:none;width:auto;height:auto;position:absolute;top:50%;left:50%;padding:2px;"><img src="<?php echo base_url('assets/images/demo_wait.gif'); ?>" width="100" height="100" /><br><span style="text-align: center;">Loading..</span></div>
<script>
    
    $(document).on("keypress", ".decimal", function () {
        $(this).val($(this).val().replace(/[^0-9\.]/g,''));
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
    });
    
    $(document).ready(function(){
        
        //publice add,edit permition start
<?php if (empty($editPermition)): ?>  
            $('.editPermission').hide();
<?php endif; ?>
<?php if (empty($addPermition)): ?>  
            $('.addPermission').hide();             
<?php endif; ?>
<?php if (empty($deletePermition)): ?>  
            $('.deletePermission').hide();             
<?php endif; ?>
        //publice add,edit permition start
        //Sale add,edit permition start
<?php if (empty($saleEditPermition)): ?>  
            $('.saleEditPermission').hide();
<?php endif; ?>
<?php if (empty($saleAddPermition)): ?>  
            $('.saleAddPermission').hide();             
<?php endif; ?>
<?php if (empty($saleDeletePermition)): ?>  
            $('.saleDeletePermission').hide();             
<?php endif; ?>
        //Sale add,edit permition end
        
        //inventory add,edit permition start
<?php if (empty($inventoryEditPermition)): ?>  
            $('.inventoryEditPermission').hide();
<?php endif; ?>
<?php if (empty($inventoryAddPermition)): ?>  

            $('.inventoryAddPermission').hide();             
<?php endif; ?>
<?php if (empty($inventoryDeletePermition)): ?>  
            $('.inventoryDeletePermission').hide();             
<?php endif; ?>
        //inventory add,edit permition end
        
        //finance add,edit permition start
<?php if (empty($financeEditPermition)): ?>  
            $('.financeEditPermission').hide();
<?php endif; ?>
<?php if (empty($financeAddPermition)): ?>  
            $('.financeAddPermission').hide();             
<?php endif; ?>
<?php if (empty($financeDeletePermition)): ?>  
            $('.financeDeletePermission').hide();             
<?php endif; ?>
        //finance add,edit permition end
    });
</script>

<script type="text/javascript">
    if('ontouchstart' in document.documentElement) document.write("<script src='<?php echo base_url() ?>assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
    
    
    
    
</script>


<!--<script src="<?php echo base_url(); ?>assets/js/jquery-2.1.4.min.js"></script>
 <![endif]-->

<!--[if IE]>
<script src="assets/js/jquery-1.11.3.min.js"></script>
<![endif]-->
<script type="text/javascript">
    if('ontouchstart' in document.documentElement) document.write("<script src='assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
</script>
<script src="<?php echo base_url(); ?>assets/common.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.dataTables.bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/dataTables.select.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery-ui.custom.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.ui.touch-punch.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/chosen.jquery.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/spinbox.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap-timepicker.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/daterangepicker.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap-datetimepicker.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap-colorpicker.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.knob.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/autosize.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.inputlimiter.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.maskedinput.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap-tag.min.js"></script>
<!-- ace scripts -->
<script src="<?php echo base_url(); ?>assets/js/ace-elements.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/ace.min.js"></script>

<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
<!-- ace scripts -->
<script src="<?php echo base_url(); ?>assets/js/ace-elements.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/ace.min.js"></script>