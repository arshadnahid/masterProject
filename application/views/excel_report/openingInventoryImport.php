<style>
    .customReadonly{
        pointer-events: none;
        cursor: not-allowed;
        background-color:green;
        color: white;
    }
    table { border: 1px solid black !important}
    tr { border: 1px solid black}
    table tr td{
        margin:0 !important ; padding: 0 !important ;
    }
    table tr th{
        margin:0 !important ; padding: 0 !important ;
    }
</style>
<table class="table table-bordered">
    <tr>
        <th align="center">#</th>
        <th align="center">Product Category</th>
        <th align="center">Product Id</th>
        <th align="center">Product Code</th>
        <th align="center">Product Name</th>
        <th align="center">Quantity</th>
        <th align="center">Unit Price</th>
        <th align="center">Total Price</th>
    </tr>
    <?php foreach ($productList as $key => $eachProduct): ?>
        <tr>
            <td class="customReadonly"><?php echo $key + 1; ?></td>
            <td class="customReadonly"><?php echo $eachProduct->productCat; ?></td>
            <td class="customReadonly"><?php echo $eachProduct->product_id; ?></td>
            <td class="customReadonly"><?php echo $eachProduct->product_code; ?></td>
            <td class="customReadonly"><?php echo $eachProduct->productName; ?></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    <?php endforeach; ?>
</table>