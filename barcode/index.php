<?php if(isset($_GET['submit'])){
 $st=$_GET['start'];
 $arr=(explode("-",$st));
 $p_id=$arr[0];
 $str=$arr[1];
 $num=$_GET['num']+1;
 for($i=0;$i<$num;$i++){
?>
<div style="padding:10px; border:1px solid gray;min-width:100px; float:left; font-size:12px;text-align:center;margin:10px;">
<span ><?php echo $_GET['product_name']; ?></span></br>
<span >Price: <?php echo $_GET['price']; ?>TK</span></br>
<img  src="<?php echo $_GET['base']; ?>/barcode/html/image.php?filetype=PNG&dpi=72&scale=1&rotation=0&font_family=Arial.ttf&font_size=8&text=<?php  echo $p_id;  ?>-<?php  echo $str++;  ?>&thickness=20&checksum=&code=BCGcode39" />
</div>
<?php } } 
else{
 ?>
<form style="width:500px;margin:0px auto;text-align:center;" action="<?php echo $_GET['base']; ?>main/barcode_gen" method="POST" >
<h2 style="font-family: arial; color: black;">POS INVENTORY BARCODE GENERATOR</h2>
<p>Product Name: <?php echo $_GET['product_name']; ?></p>
<p>Price: <?php echo $_GET['price']; ?></p>
<input type="hidden" name="product_name" value="<?php echo $_GET['product_name']; ?>" />
<input type="hidden" name="price" value="<?php echo $_GET['price']; ?>" />
From:<input type="text" name="start" value="<?php echo $_GET['product_id']; ?>-<?php echo $_GET['barcode']; ?>" />
Quantity: <input type="text" name="num" />
<input type="submit" name="submit" value="Generate" />
</form>
<?php } ?>