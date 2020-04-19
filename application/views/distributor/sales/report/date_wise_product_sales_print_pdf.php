<html>
    <head>
        <title>User Report</title>
        <style>
            body { font: 12px/1.4 "Helvetica Neue",Helvetica,Arial,sans-serif; }
            #page-wrap { width: 800px; margin: 0 auto; }
            textarea { border: 0; font: 14px "Helvetica Neue",Helvetica,Arial,sans-serif; overflow: hidden; resize: none; }
            table { border-collapse: collapse; }
            table td, table th { border: 1px solid black; padding: 5px; font-weight: normal; }
            #header { height: 10px; width: 100%; margin: 20px 0; background: #03a9f4; text-align: center; color: white; font: bold 13px "Helvetica Neue",Helvetica,Arial,sans-serif; text-decoration: uppercase; letter-spacing: 20px; padding: 8px 0px; }
            #address { width: 250px; height: 150px; float: left; }
            #customer { overflow: hidden; }
            #logo { text-align: right; float: right; position: relative; margin-top: -90px; border: 1px solid #fff; max-width: 540px; max-height: 100px; overflow: hidden; }
            #logohelp { text-align: left; display: none; font-style: italic; padding: 10px 5px;}
            #logohelp input { margin-bottom: 5px; }
            .edit #logohelp { display: block; }
            .edit #save-logo, .edit #cancel-logo { display: inline; }
            .edit #image, #save-logo, #cancel-logo, .edit #change-logo, .edit #delete-logo { display: none; }
            #customer-title { font-size: 20px; float: left; }
            #meta { margin-top: 1px; width: 300px; float: right; }
            #meta td { text-align: right;  }
            #meta td.meta-head { text-align: left; background: #eee; }
            #meta td textarea { width: 100%; height: 20px; text-align: right; }
            #items { clear: both; width: 100%; margin: 20px 0 0 0; border: 1px solid black; }
            #items th { background: #eee; }
            #items textarea { width: 80px; height: 50px; }
            #items tr.item-row td { border: 0;  }
            #items td.description { width: 300px; }
            #items td.item-name { width: 175px; }
            #items td.description textarea, #items td.item-name textarea { width: 100%; }
            #items td.total-line { border-right: 0; text-align: right; }
            #items td.total-value { border-left: 0; padding: 10px; }
            #items td.total-value textarea { height: 20px; background: none; }
            #items td.balance { background: #eee; }
            #items td.blank { border: 0; }
            #terms { text-align: center; margin: 90px 0 0 0; }
            #terms h5 { text-transform: uppercase; font: 13px "Helvetica Neue",Helvetica,Arial,sans-serif; letter-spacing: 10px; border-bottom: 1px solid black; padding: 0 0 8px 0; margin: 0 0 8px 0; }
            #terms textarea { width: 100%; text-align: center;}
            textarea:hover, textarea:focus, #items td.total-value textarea:hover, #items td.total-value textarea:focus, .delete:hover { background-color:#EEFF88; }
            .delete-wpr { position: relative; }
            .delete { display: block; color: #000; text-decoration: none; position: absolute; background: #EEEEEE; font-weight: bold; padding: 0px 3px; border: 1px solid; top: -6px; left: -22px; font-family: Verdana; font-size: 12px; }
        </style>
    </head>
    <body >
        <div id="terms" style="line-height:5px;position:fixed;z-index:99; top: -40px;" >
            <h3 >M/S Master trades</h3>
            <p>Address:Navana Zohura.</p>
            <p> 28,Kazi Nazrul Islam Avenue</p>
            <p>Phone:01710000000</p>
            <p>Daily Sales Statement Report</p>
            <p>Form:01-05-2019 To:30-05-2019</p>
        </div>

<!--        <table id="items" style="margin-left:20px;margin-right:20px">
            <thead>
                <tr>

                    <th class="text-center">Product Name</th>
                    <th class="text-center">Sales Qty</th>
                    <th class="text-center">Sales Price</th>
                    <th class="text-center">Total Sales</th>


                </tr>

            </thead>
            <tbody>
        <?php
        foreach ($daily_sales_statement as $ind => $element2) {
            ?>
                            <tr>

                                <td style="text-align: center">
            <?php echo $element2->productName; ?>
                                </td>
                                <td style="text-align: center">
            <?php echo $element2->sales_qty; ?>
                                </td>
                                <td style="text-align: right">
            <?php echo $element2->sales_price; ?>
                                </td>

                                <td style="text-align: right">
            <?php
            $price = $element2->sales_qty * $element2->sales_price;
            echo $price;
            $ttPrice = $ttPrice + $price;
            ?>
                                </td>

                            </tr>
        <?php }
        ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" style="text-align: right">
        <?php echo $ttPrice ?>
                    </td>
                </tr>
            </tfoot>

        </table>-->
        <table style="width:735px;margin: 0 auto;color:#333;margin-left:20px;margin-right:20px;">
            <thead>
                <tr>
                    <th class="text-center">Sales Date</th>
                    <th class="text-center">Product Name</th>
                    <th class="text-center">Sales Qty</th>
                    <th class="text-center">Sales Price</th>
                    <th class="text-center">Total Sales</th>


                </tr>

            </thead>
            <tbody>
                <tr>
                    <td>
                        2019-07-14 </td>
                    <td>
                        Refill  12 Kg [ BM Energy ] </td>
                    <td>
                        2  </td>
                    <td style="text-align: right">
                        500.0000  </td>

                    <td style="text-align: right">
                        1000 </td>

                </tr>
                <tr>
                    <td>
                        2019-07-14 </td>
                    <td>
                        Refill  12 Kg [ BM Energy ] </td>
                    <td>
                        2  </td>
                    <td style="text-align: right">
                        500.0000  </td>

                    <td style="text-align: right">
                        1000 </td>

                </tr>
                <tr>
                    <td>
                        2019-07-14 </td>
                    <td>
                        Refill  12 Kg [ BM Energy ] </td>
                    <td>
                        2  </td>
                    <td style="text-align: right">
                        500.0000  </td>

                    <td style="text-align: right">
                        1000 </td>

                </tr>
                <tr>
                    <td>
                        2019-07-14 </td>
                    <td>
                        Refill  12 Kg [ BM Energy ] </td>
                    <td>
                        2  </td>
                    <td style="text-align: right">
                        500.0000  </td>

                    <td style="text-align: right">
                        1000 </td>

                </tr>
                <tr>
                    <td>
                        2019-07-14 </td>
                    <td>
                        Refill  12 Kg [ BM Energy ] </td>
                    <td>
                        2  </td>
                    <td style="text-align: right">
                        500.0000  </td>

                    <td style="text-align: right">
                        1000 </td>

                </tr>
                <tr>
                    <td>
                        2019-07-14 </td>
                    <td>
                        Refill  12 Kg [ BM Energy ] </td>
                    <td>
                        2  </td>
                    <td style="text-align: right">
                        500.0000  </td>

                    <td style="text-align: right">
                        1000 </td>

                </tr>
                <tr>
                    <td>
                        2019-07-14 </td>
                    <td>
                        Refill  12 Kg [ BM Energy ] </td>
                    <td>
                        2  </td>
                    <td style="text-align: right">
                        500.0000  </td>

                    <td style="text-align: right">
                        1000 </td>

                </tr>
                <tr>
                    <td>
                        2019-07-14 </td>
                    <td>
                        Refill  12 Kg [ BM Energy ] </td>
                    <td>
                        2  </td>
                    <td style="text-align: right">
                        500.0000  </td>

                    <td style="text-align: right">
                        1000 </td>

                </tr>
                <tr>
                    <td>
                        2019-07-14 </td>
                    <td>
                        Refill  12 Kg [ BM Energy ] </td>
                    <td>
                        2  </td>
                    <td style="text-align: right">
                        500.0000  </td>

                    <td style="text-align: right">
                        1000 </td>

                </tr>
                <tr>
                    <td>
                        2019-07-14 </td>
                    <td>
                        Refill  12 Kg [ BM Energy ] </td>
                    <td>
                        2  </td>
                    <td style="text-align: right">
                        500.0000  </td>

                    <td style="text-align: right">
                        1000 </td>

                </tr>
                <tr>
                    <td>
                        2019-07-14 </td>
                    <td>
                        Refill  12 Kg [ BM Energy ] </td>
                    <td>
                        2  </td>
                    <td style="text-align: right">
                        500.0000  </td>

                    <td style="text-align: right">
                        1000 </td>

                </tr>
                vacanciesv<tr>
                    <td>
                        2019-07-14 </td>
                    <td>
                        Refill  12 Kg [ BM Energy ] </td>
                    <td>
                        2  </td>
                    <td style="text-align: right">
                        500.0000  </td>

                    <td style="text-align: right">
                        1000 </td>

                </tr>
                <tr>
                    <td>
                        2019-07-14 </td>
                    <td>
                        Refill  12 Kg [ BM Energy ] </td>
                    <td>
                        2  </td>
                    <td style="text-align: right">
                        500.0000  </td>

                    <td style="text-align: right">
                        1000 </td>

                </tr>
                <tr>
                    <td>
                        2019-07-14 </td>
                    <td>
                        Refill  12 Kg [ BM Energy ] </td>
                    <td>
                        2  </td>
                    <td style="text-align: right">
                        500.0000  </td>

                    <td style="text-align: right">
                        1000 </td>

                </tr>
                <tr>
                    <td>
                        2019-07-14 </td>
                    <td>
                        Refill  12 Kg [ BM Energy ] </td>
                    <td>
                        2  </td>
                    <td style="text-align: right">
                        500.0000  </td>

                    <td style="text-align: right">
                        1000 </td>

                </tr>
                <tr>
                    <td>
                        2019-07-14 </td>
                    <td>
                        Refill  12 Kg [ BM Energy ] </td>
                    <td>
                        2  </td>
                    <td style="text-align: right">
                        500.0000  </td>

                    <td style="text-align: right">
                        1000 </td>

                </tr>
                <tr>
                    <td>
                        2019-07-14 </td>
                    <td>
                        Refill  12 Kg [ BM Energy ] </td>
                    <td>
                        2  </td>
                    <td style="text-align: right">
                        500.0000  </td>

                    <td style="text-align: right">
                        1000 </td>

                </tr>
                <tr>
                    <td>
                        2019-07-14 </td>
                    <td>
                        Refill  12 Kg [ BM Energy ] </td>
                    <td>
                        2  </td>
                    <td style="text-align: right">
                        500.0000  </td>

                    <td style="text-align: right">
                        1000 </td>

                </tr>
                <tr>
                    <td>
                        2019-07-14 </td>
                    <td>
                        Refill  12 Kg [ BM Energy ] </td>
                    <td>
                        2  </td>
                    <td style="text-align: right">
                        500.0000  </td>

                    <td style="text-align: right">
                        1000 </td>

                </tr>
                <tr>
                    <td>
                        2019-07-14 </td>
                    <td>
                        Refill  12 Kg [ BM Energy ] </td>
                    <td>
                        2  </td>
                    <td style="text-align: right">
                        500.0000  </td>

                    <td style="text-align: right">
                        1000 </td>

                </tr>
                <tr>
                    <td>
                        2019-07-14 </td>
                    <td>
                        Refill  12 Kg [ BM Energy ] </td>
                    <td>
                        2  </td>
                    <td style="text-align: right">
                        500.0000  </td>

                    <td style="text-align: right">
                        1000 </td>

                </tr>
                <tr>
                    <td>
                        2019-07-14 </td>
                    <td>
                        Refill  12 Kg [ BM Energy ] </td>
                    <td>
                        2  </td>
                    <td style="text-align: right">
                        500.0000  </td>

                    <td style="text-align: right">
                        1000 </td>

                </tr>
                vacanciesv<tr>
                    <td>
                        2019-07-14 </td>
                    <td>
                        Refill  12 Kg [ BM Energy ] </td>
                    <td>
                        2  </td>
                    <td style="text-align: right">
                        500.0000  </td>

                    <td style="text-align: right">
                        1000 </td>

                </tr>
                <tr>
                    <td>
                        2019-07-14 </td>
                    <td>
                        Refill  12 Kg [ BM Energy ] </td>
                    <td>
                        2  </td>
                    <td style="text-align: right">
                        500.0000  </td>

                    <td style="text-align: right">
                        1000 </td>

                </tr>
                <tr>
                    <td>
                        2019-07-14 </td>
                    <td>
                        Refill  12 Kg [ BM Energy ] </td>
                    <td>
                        2  </td>
                    <td style="text-align: right">
                        500.0000  </td>

                    <td style="text-align: right">
                        1000 </td>

                </tr>
                <tr>
                    <td>
                        2019-07-14 </td>
                    <td>
                        Refill  12 Kg [ BM Energy ] </td>
                    <td>
                        2  </td>
                    <td style="text-align: right">
                        500.0000  </td>

                    <td style="text-align: right">
                        1000 </td>

                </tr>
                <tr>
                    <td>
                        2019-07-14 </td>
                    <td>
                        Refill  12 Kg [ BM Energy ] </td>
                    <td>
                        2  </td>
                    <td style="text-align: right">
                        500.0000  </td>

                    <td style="text-align: right">
                        1000 </td>

                </tr>
                <tr>
                    <td>
                        2019-07-14 </td>
                    <td>
                        Refill  12 Kg [ BM Energy ] </td>
                    <td>
                        2  </td>
                    <td style="text-align: right">
                        500.0000  </td>

                    <td style="text-align: right">
                        1000 </td>

                </tr>
                <tr>
                    <td>
                        2019-07-14 </td>
                    <td>
                        Refill  12 Kg [ BM Energy ] </td>
                    <td>
                        2  </td>
                    <td style="text-align: right">
                        500.0000  </td>

                    <td style="text-align: right">
                        1000 </td>

                </tr>
                <tr>
                    <td>
                        2019-07-14 </td>
                    <td>
                        Refill  12 Kg [ BM Energy ] </td>
                    <td>
                        2  </td>
                    <td style="text-align: right">
                        500.0000  </td>

                    <td style="text-align: right">
                        1000 </td>

                </tr>
                <tr>
                    <td>
                        2019-07-14 </td>
                    <td>
                        Refill  12 Kg [ BM Energy ] </td>
                    <td>
                        2  </td>
                    <td style="text-align: right">
                        500.0000  </td>

                    <td style="text-align: right">
                        1000 </td>

                </tr>
                <tr>
                    <td>
                        2019-07-14 </td>
                    <td>
                        Refill  12 Kg [ BM Energy ] </td>
                    <td>
                        2  </td>
                    <td style="text-align: right">
                        500.0000  </td>

                    <td style="text-align: right">
                        1000 </td>

                </tr>
                <tr>
                    <td>
                        2019-07-14 </td>
                    <td>
                        Refill  12 Kg [ BM Energy ] </td>
                    <td>
                        2  </td>
                    <td style="text-align: right">
                        500.0000  </td>

                    <td style="text-align: right">
                        1000 </td>

                </tr>
                vacanciesv<tr>
                    <td>
                        2019-07-14 </td>
                    <td>
                        Refill  12 Kg [ BM Energy ] </td>
                    <td>
                        2  </td>
                    <td style="text-align: right">
                        500.0000  </td>

                    <td style="text-align: right">
                        1000 </td>

                </tr>
                <tr>
                    <td>
                        2019-07-14 </td>
                    <td>
                        Refill  12 Kg [ BM Energy ] </td>
                    <td>
                        2  </td>
                    <td style="text-align: right">
                        500.0000  </td>

                    <td style="text-align: right">
                        1000 </td>

                </tr>
                <tr>
                    <td>
                        2019-07-14 </td>
                    <td>
                        Refill  12 Kg [ BM Energy ] </td>
                    <td>
                        2  </td>
                    <td style="text-align: right">
                        500.0000  </td>

                    <td style="text-align: right">
                        1000 </td>

                </tr>
                <tr>
                    <td>
                        2019-07-14 </td>
                    <td>
                        Refill  12 Kg [ BM Energy ] </td>
                    <td>
                        2  </td>
                    <td style="text-align: right">
                        500.0000  </td>

                    <td style="text-align: right">
                        1000 </td>

                </tr>
                <tr>
                    <td>
                        2019-07-14 </td>
                    <td>
                        Refill  12 Kg [ BM Energy ] </td>
                    <td>
                        2  </td>
                    <td style="text-align: right">
                        500.0000  </td>

                    <td style="text-align: right">
                        1000 </td>

                </tr>
                <tr>
                    <td>
                        2019-07-14 </td>
                    <td>
                        Refill  12 Kg [ BM Energy ] </td>
                    <td>
                        2  </td>
                    <td style="text-align: right">
                        500.0000  </td>

                    <td style="text-align: right">
                        1000 </td>

                </tr>
                <tr>
                    <td>
                        2019-07-14 </td>
                    <td>
                        Refill  12 Kg [ BM Energy ] </td>
                    <td>
                        2  </td>
                    <td style="text-align: right">
                        500.0000  </td>

                    <td style="text-align: right">
                        1000 </td>

                </tr>
                <tr>
                    <td>
                        2019-07-14 </td>
                    <td>
                        Refill  12 Kg [ BM Energy ] </td>
                    <td>
                        2  </td>
                    <td style="text-align: right">
                        500.0000  </td>

                    <td style="text-align: right">
                        1000 </td>

                </tr>
                <tr>
                    <td>
                        2019-07-14 </td>
                    <td>
                        Refill  12 Kg [ BM Energy ] </td>
                    <td>
                        2  </td>
                    <td style="text-align: right">
                        500.0000  </td>

                    <td style="text-align: right">
                        1000 </td>

                </tr>
                <tr>
                    <td>
                        2019-07-14 </td>
                    <td>
                        Refill  12 Kg [ BM Energy ] </td>
                    <td>
                        2  </td>
                    <td style="text-align: right">
                        500.0000  </td>

                    <td style="text-align: right">
                        1000 </td>

                </tr>
                <tr>
                    <td>
                        2019-07-14 </td>
                    <td>
                        Refill  12 Kg [ BM Energy ] </td>
                    <td>
                        2  </td>
                    <td style="text-align: right">
                        500.0000  </td>

                    <td style="text-align: right">
                        1000 </td>

                </tr>
                vacanciesv<tr>
                    <td>
                        2019-07-14 </td>
                    <td>
                        Refill  12 Kg [ BM Energy ] </td>
                    <td>
                        2  </td>
                    <td style="text-align: right">
                        500.0000  </td>

                    <td style="text-align: right">
                        1000 </td>

                </tr>
                <tr>
                    <td>
                        2019-07-14 </td>
                    <td>
                        Refill  12 Kg [ BM Energy ] </td>
                    <td>
                        2  </td>
                    <td style="text-align: right">
                        500.0000  </td>

                    <td style="text-align: right">
                        1000 </td>

                </tr>
                <tr>
                    <td>
                        2019-07-14 </td>
                    <td>
                        Refill  12 Kg [ BM Energy ] </td>
                    <td>
                        2  </td>
                    <td style="text-align: right">
                        500.0000  </td>

                    <td style="text-align: right">
                        1000 </td>

                </tr>
                <tr>
                    <td>
                        2019-07-14 </td>
                    <td>
                        Refill  12 Kg [ BM Energy ] </td>
                    <td>
                        2  </td>
                    <td style="text-align: right">
                        500.0000  </td>

                    <td style="text-align: right">
                        1000 </td>

                </tr>
                <tr>
                    <td>
                        2019-07-14 </td>
                    <td>
                        Refill  12 Kg [ BM Energy ] </td>
                    <td>
                        2  </td>
                    <td style="text-align: right">
                        500.0000  </td>

                    <td style="text-align: right">
                        1000 </td>

                </tr>
                <tr>
                    <td>
                        2019-07-14 </td>
                    <td>
                        Refill  12 Kg [ BM Energy ] </td>
                    <td>
                        2  </td>
                    <td style="text-align: right">
                        500.0000  </td>

                    <td style="text-align: right">
                        1000 </td>

                </tr>
                <tr>
                    <td>
                        2019-07-14 </td>
                    <td>
                        Refill  12 Kg [ BM Energy ] </td>
                    <td>
                        2  </td>
                    <td style="text-align: right">
                        500.0000  </td>

                    <td style="text-align: right">
                        1000 </td>

                </tr>
                <tr>
                    <td>
                        2019-07-14 </td>
                    <td>
                        Refill  12 Kg [ BM Energy ] </td>
                    <td>
                        2  </td>
                    <td style="text-align: right">
                        500.0000  </td>

                    <td style="text-align: right">
                        1000 </td>

                </tr>
                <tr>
                    <td>
                        2019-07-14 </td>
                    <td>
                        Refill  12 Kg [ BM Energy ] </td>
                    <td>
                        2  </td>
                    <td style="text-align: right">
                        500.0000  </td>

                    <td style="text-align: right">
                        1000 </td>

                </tr>
                <tr>
                    <td>
                        2019-07-14 </td>
                    <td>
                        Refill  12 Kg [ BM Energy ] </td>
                    <td>
                        2  </td>
                    <td style="text-align: right">
                        500.0000  </td>

                    <td style="text-align: right">
                        1000 </td>

                </tr>
                <tr>
                    <td>
                        2019-07-14 </td>
                    <td>
                        Refill  12 Kg [ BM Energy ] </td>
                    <td>
                        2  </td>
                    <td style="text-align: right">
                        500.0000  </td>

                    <td style="text-align: right">
                        1000 </td>

                </tr>
                vacanciesv<tr>
                    <td>
                        2019-07-14 </td>
                    <td>
                        Refill  12 Kg [ BM Energy ] </td>
                    <td>
                        2  </td>
                    <td style="text-align: right">
                        500.0000  </td>

                    <td style="text-align: right">
                        1000 </td>

                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" style="text-align: right">
                       Total Sales: 1000 </td>
                </tr>
            </tfoot>

        </table>
        <div  style="position: fixed;bottom: -17px;font-size:11px;border-top: 1px  #E5E5E5; color:#333; text-align: center;margin: 0 auto;font-weight: normal;" >
            <p style="border-top: 1px double #E5E5E5;">Copyright ©2019 AEL</p>
        </div>
        
    </body>
</html>


