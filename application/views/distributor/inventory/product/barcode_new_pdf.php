<?php
/**
 * Created by PhpStorm.
 * User: Nahid
 * Date: 5/11/2020
 * Time: 2:53 AM
 */?>

<?php
foreach ($barcodes as $key => $productInfo) {
    $c = 1 + $c;
    ?>
    <div id="printableArea" style="padding:2px 10px; border:1px solid #efebeb;;min-width:100px; float:left; font-size:2px;text-align:center;margin:10px;">
        <b style="font-size:10px;"><span><?php echo $productInfo->productName ; ?>
        </b></br>
        <span style="font-size:12px;margin-top:-10px">
                                                        <?php echo $productInfo->brandName;?>
                                                    </span>
        </br>
        <img src="<?php echo base_url(); ?>/barcode/html/image.php?filetype=PNG&dpi=92&scale=1&rotation=0&font_family=Arial.ttf&font_size=10&text=<?php echo $productInfo->product_code; ?>&thickness=45&checksum=&code=BCGcode39"/>
        <br>
        <strong><span style="font-size:12px; margin-right:10px"><?php if($productInfo->salesPrice!='') echo 'MRP : '. $productInfo->salesPrice ?>/-</span></strong>
    </div>

    <?php
    if ($c%21==0) {
        echo '</div><div class="clearfix"></div>';
        $pageNo = $pageNo+1;
    }
    if ($pageNo>1 && $c%21==0 && ($c!= count($barcodes))) {


        echo '<div class="barcodea4">';
        // $c = 0;
    }

}


?>
