<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */


if ( ! function_exists('get_phrase'))
{
	function get_phrase($phrase = '') {
		$CI	=&	get_instance();
        $CI->load->helper('language');


        $phrase=ucwords(implode('_', preg_split("/[\s,]+/", trim($phrase))));
        //New Sale Invoice to =>>>>> New_Sale_Invoice

        $current_language=$CI->session->userdata('site_lang') ;
        $convert_text=$CI->lang->line($phrase);
        /*log_message('error','this is nahid'.print_r($convert_text,true));*/
		// return the current sessioned language field of according phrase, else return uppercase spaced word
		if (isset($convert_text) && $convert_text !="")
		    if($current_language=='english'){
                return ucwords(str_replace('_',' ',$convert_text));
            }else{
                return $convert_text;
            }

		else

                return ucwords(str_replace('_',' ',$phrase));
           
			//ucwords(str_replace('_',' ',$phrase));

        $query="SELECT product_id,category_id,unit_id,brand_id, 
 BrandName, productName,CategoryName, unitTtile,
  IFNULL(OP_quantity,0) OP_quantity,
IFNULL(OP_UPrice,0) OP_UPrice ,
IFNULL(OP_Amount,0) OP_Amount ,

IFNULL(Pur_Qty,0) AS Pur_Qty,
IFNULL(Pur_UPrice,0) AS Pur_UPrice,
IFNULL(Pur_Amount,0) AS Pur_Amount,

IFNULL(Sales_Qty,0) Sales_Qty,
IFNULL(Sales_UPrice,0) Sales_UPrice,
IFNULL(Sales_Amount,0) Sales_Amount,

IFNULL((IFNULL(OP_quantity,0)+IFNULL(Pur_Qty,0)-IFNULL(Sales_Qty,0)),0) Closing_Qnty,

IFNULL((IFNULL(OP_Amount,0)+ IFNULL(Pur_Amount,0) )/ NULLIF(( IFNULL(OP_quantity,0)+IFNULL(Pur_Qty,0)),0)  ,0)  Closing_UPrice,

IFNULL((IFNULL((IFNULL(OP_quantity,0)+IFNULL(Pur_Qty,0)-IFNULL(Sales_Qty,0)),0))*(IFNULL((IFNULL(OP_Amount,0)+ IFNULL(Pur_Amount,0) )/ NULLIF(( IFNULL(OP_quantity,0)+IFNULL(Pur_Qty,0)),0)  ,0) ),0) Closing_Amount

 FROM 


( SELECT BaseT.product_id,BaseT.category_id,BaseT.unit_id,BaseT.brand_id, brand.brandName BrandName,BaseT.productName, productcategory.title CategoryName,unit.unitTtile,

  IFNULL(OP_quantity,0) OP_quantity,
IFNULL(OP_UPrice,0) OP_UPrice 
,IFNULL(OP_Amount,0) OP_Amount ,

(IFNULL(quantity_P,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0) + IFNULL(sales_SRe,0)) Pur_Qty, 
((IFNULL(INV_in_qty,0)*IFNULL(INV_IN_UPrice,0))+(IFNULL(Srt_quantity,0)*IFNULL(Srt_UPrice,0))+(IFNULL(U_Price_Purchase_In,0)*IFNULL(quantity_P,0)) +
(IFNULL(sales_SRe,0)* IFNULL(U_Price_sales_SRe,0))  ) /
NULLIF(((IFNULL(quantity_P,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0) + IFNULL(sales_SRe,0))),0) Pur_UPrice,
IFNULL(((IFNULL(quantity_P,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0) + IFNULL(sales_SRe,0)))*(((IFNULL(INV_in_qty,0)*IFNULL(INV_IN_UPrice,0))+(IFNULL(Srt_quantity,0)*IFNULL(Srt_UPrice,0))+(IFNULL(U_Price_Purchase_In,0)*IFNULL(quantity_P,0)) +
(IFNULL(sales_SRe,0)* IFNULL(U_Price_sales_SRe,0))  ) /
NULLIF(((IFNULL(quantity_P,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0) + IFNULL(sales_SRe,0))),0)),0) Pur_Amount,

 (IFNULL(INV_out_qty,0) + IFNULL(sales_out_Qty,0) + IFNULL(quantity_PRe,0)) Sales_Qty,
 
 ((IFNULL(INV_OUT_UPrice,0)*IFNULL(INV_out_qty,0)) + (IFNULL(sales_out_Qty,0) * IFNULL(U_Price_sales_out,0))
  + (IFNULL(quantity_PRe,0) * IFNULL(U_Price_Pre,0) ) ) /
  NULLIF(((IFNULL(INV_out_qty,0) + IFNULL(sales_out_Qty,0) + IFNULL(quantity_PRe,0)) ),0) Sales_UPrice,
 
 IFNULL(((IFNULL(INV_out_qty,0) + IFNULL(sales_out_Qty,0) + IFNULL(quantity_PRe,0)))*( ((IFNULL(INV_OUT_UPrice,0)*IFNULL(INV_out_qty,0)) + (IFNULL(sales_out_Qty,0) * IFNULL(U_Price_sales_out,0))
  + (IFNULL(quantity_PRe,0) * IFNULL(U_Price_Pre,0) ) ) /
  NULLIF(((IFNULL(INV_out_qty,0) + IFNULL(sales_out_Qty,0) + IFNULL(quantity_PRe,0)) ),0)),0) Sales_Amount
 
FROM 
 (
SELECT product.product_id, product.productName,product.category_id ,product.unit_id,product.brand_id
  FROM  
 (SELECT product_id  FROM   purchase_details
  union  
  SELECT product_id FROM   purchase_return_details  
  )   
    T2 LEFT OUTER JOIN 
   product  
  ON product.product_id=T2.product_id  WHERE product.category_id=1) BaseT LEFT OUTER JOIN
   
  
                      productcategory ON BaseT.category_id = productcategory.category_id LEFT OUTER JOIN
                      unit ON BaseT.unit_id = unit.unit_id LEFT OUTER JOIN
                      brand ON BaseT.brand_id = brand.brandId LEFT OUTER JOIN
                      
  ( 

SELECT
 BaseT.product_id, BaseT.productName, BaseT.category_id,productcategory.title,
 BaseT.unit_id, unit.unitTtile , BaseT.brand_id,brand.brandName,
/*Opening*/
 

(IFNULL(( IFNULL(quantity_Open,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0) + IFNULL(sales_SRe,0) ),0)) -
(IFNULL(( IFNULL(sales_out_Qty,0) + IFNULL(INV_out_qty,0)   + IFNULL(quantity_PRe,0)),0))  
OP_quantity  ,
 
 
IFNULL((IFNULL(Amount_Purchase_In,0) +  IFNULL(Srt_Amount,0)+IFNULL(Amount_sales_SRe,0) + IFNULL(INV_IN_Amount,0))
 /NULLIF(IFNULL(( IFNULL(quantity_Open,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0) + IFNULL(sales_SRe,0)),0) ,0 ),0)
  OP_UPrice,



IFNULL(((IFNULL(( IFNULL(quantity_Open,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0) + IFNULL(sales_SRe,0) ),0)) -
(IFNULL(( IFNULL(sales_out_Qty,0) + IFNULL(INV_out_qty,0)   + IFNULL(quantity_PRe,0)),0)) )*(IFNULL((IFNULL(Amount_Purchase_In,0) +  IFNULL(Srt_Amount,0)+IFNULL(Amount_sales_SRe,0) + IFNULL(INV_IN_Amount,0))
 /NULLIF(IFNULL(( IFNULL(quantity_Open,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0) + IFNULL(sales_SRe,0)),0) ,0 ),0)),0) OP_Amount
 
 
/*END Opening*/
 

 
 FROM 
 (
SELECT product.product_id, product.productName,product.category_id ,product.unit_id,product.brand_id
  FROM  product LEFT OUTER JOIN 
 (SELECT product_id  FROM   purchase_details
  union  
  SELECT product_id FROM   purchase_return_details  
  )  T2 ON product.product_id=T2.product_id  WHERE product.category_id=1) BaseT LEFT OUTER JOIN
   
  
                      productcategory ON BaseT.category_id = productcategory.category_id LEFT OUTER JOIN
                      unit ON BaseT.unit_id = unit.unit_id LEFT OUTER JOIN
                      brand ON BaseT.brand_id = brand.brandId LEFT OUTER JOIN
                      
 /*Purchase_In*/
                     
(SELECT    
purchase_details.product_id,SUM(IFNULL( purchase_details.quantity,0)) AS quantity_P, 
IFNULL((SUM(IFNULL(purchase_details.quantity*purchase_details. unit_price,0)))/
NULLIF(SUM(IFNULL(purchase_details.quantity,0)),0),0) U_Price_Purchase_In,

IFNULL((SUM(IFNULL( purchase_details.quantity,0)))*( 
IFNULL((SUM(IFNULL(purchase_details.quantity*purchase_details. unit_price,0)))/
NULLIF(SUM(IFNULL(purchase_details.quantity,0)),0),0)),0) Amount_Purchase_In 
  

FROM         purchase_invoice_info LEFT OUTER JOIN
purchase_details ON purchase_invoice_info.purchase_invoice_id = purchase_details.purchase_invoice_id  
WHERE   purchase_details.is_active='Y' AND purchase_details.is_delete='N'  
 AND purchase_invoice_info.invoice_date < '2020-01-08'
  
 

GROUP BY   purchase_details.product_id  ) Purchase_In ON  BaseT.product_id= Purchase_In.product_id  Left outer JOIN
/*END Purchase_In*/

/*Opening*/

(SELECT    
purchase_details.product_id,
SUM(IFNULL( purchase_details.quantity,0)) AS quantity_Open, 
 IFNULL((SUM(IFNULL(purchase_details.quantity,0) * IFNULL(purchase_details.unit_price,0)))/
 NULLIF(SUM(IFNULL( purchase_details.quantity,0)),0),0) U_Price_opening  ,
 
 IFNULL((SUM(IFNULL( purchase_details.quantity,0))) *  (
 IFNULL((SUM(IFNULL(purchase_details.quantity,0) * IFNULL(purchase_details.unit_price,0)))/
 NULLIF(SUM(IFNULL( purchase_details.quantity,0)),0),0)),0) Amount_opening

   

FROM         purchase_invoice_info LEFT OUTER JOIN
purchase_details ON purchase_invoice_info.purchase_invoice_id = purchase_details.purchase_invoice_id 
 
WHERE   purchase_details.is_active='Y' AND purchase_details.is_delete='N'  
  AND purchase_invoice_info.invoice_date < '2020-01-08'   
 GROUP BY   purchase_details.product_id  ) Opening 
 ON Opening.product_id=BaseT.product_id  LEFT  OUTER JOIN
 
/*END Opening*/

/*sales_out*/

(SELECT    
sales_details.product_id,SUM(IFNULL( sales_details.quantity,0)) AS sales_out_Qty, 

IFNULL((SUM(IFNULL(sales_details.quantity,0) * IFNULL(sales_details. unit_price,0)))/
NULLIF(SUM(IFNULL( sales_details.quantity,0)),0),0) U_Price_sales_out  ,

IFNULL((SUM(IFNULL( sales_details.quantity,0))) * (

IFNULL((SUM(IFNULL(sales_details.quantity,0) * IFNULL(sales_details. unit_price,0)))/
NULLIF(SUM(IFNULL( sales_details.quantity,0)),0),0)),0) Amount_sales_out 


FROM         sales_invoice_info LEFT OUTER JOIN
sales_details ON sales_invoice_info.sales_invoice_id = sales_details.sales_invoice_id 

WHERE   sales_details.is_active='Y' AND sales_details.is_delete='N'  
 AND sales_invoice_info.invoice_date < '2020-01-08'
  
 

GROUP BY   sales_details.product_id  ) sales_out ON BaseT.product_id=sales_out.product_id 
/*END sales_out*/

/*ReturnPurchase*/
LEFT OUTER JOIN (
SELECT    
purchase_return_details.product_id , 

IFNULL(SUM( purchase_return_details.return_quantity ),0) AS quantity_PRe  , 
 IFNULL((SUM(purchase_return_details.return_quantity*purchase_return_details.unit_price))
 /  NULLIF(SUM(IFNULL( purchase_return_details.return_quantity,0)),0)      ,0) U_Price_PRe,
 
 IFNULL((IFNULL(SUM( purchase_return_details.return_quantity ),0)) * (
 IFNULL((SUM(purchase_return_details.return_quantity*purchase_return_details.unit_price))
 /  NULLIF(SUM(IFNULL( purchase_return_details.return_quantity,0)),0),0)),0)  Amount_PRe
 
 

FROM         purchase_invoice_info LEFT OUTER JOIN
purchase_details ON purchase_invoice_info.purchase_invoice_id = purchase_details.purchase_invoice_id LEFT OUTER JOIN
purchase_return_details ON purchase_invoice_info.purchase_invoice_id = purchase_return_details.purchase_invoice_id 
  AND
purchase_details.purchase_details_id = purchase_return_details.purchase_details_id 
 


WHERE   purchase_details.is_active='Y' AND purchase_details.is_delete='N' AND   
IFNULL(purchase_return_details.is_active,'Y')='Y' AND IFNULL(purchase_return_details.is_delete,'N')='N' 
AND purchase_invoice_info.invoice_date < '2020-01-08'
  

GROUP BY   purchase_return_details.product_id) Ret_Purchase ON   BaseT.product_id= Ret_Purchase.product_id 
/*END ReturnPurchase*/

/*ReturnOpening*/
LEFT OUTER JOIN (
SELECT    
purchase_return_details.product_id , 

IFNULL(SUM(IFNULL( purchase_return_details.return_quantity ,0)),0) AS quantity_OpenRe,

 IFNULL((SUM(IFNULL(purchase_return_details.return_quantity,0) * IFNULL(purchase_return_details.unit_price,0)))
 / NULLIF(SUM(IFNULL(purchase_return_details.return_quantity,0)),0),0) U_Price_openingRe ,
 
 IFNULL( (SUM(IFNULL( purchase_return_details.return_quantity ,0))) *( 

  SUM(IFNULL(purchase_return_details.return_quantity,0)  * IFNULL(purchase_return_details.unit_price,0)) 
 / NULLIF(SUM(IFNULL(purchase_return_details.return_quantity,0)),0)),0)  Amount_openingRe
  

FROM         purchase_invoice_info LEFT OUTER JOIN
purchase_details ON purchase_invoice_info.purchase_invoice_id = purchase_details.purchase_invoice_id LEFT OUTER JOIN
purchase_return_details ON purchase_invoice_info.purchase_invoice_id = purchase_return_details.purchase_invoice_id 
 
 AND
purchase_details.purchase_details_id = purchase_return_details.purchase_details_id 
 

WHERE   purchase_details.is_active='Y' AND purchase_details.is_delete='N' AND   
IFNULL(purchase_return_details.is_active,'Y')='Y' AND IFNULL(purchase_return_details.is_delete,'N')='N' 
AND purchase_invoice_info.invoice_date < '2020-01-08'
  

GROUP BY   purchase_return_details.product_id) Ret_Opening ON   BaseT.product_id= Ret_Opening.product_id
/*END ReturnOpening*/

/*ReturnSales*/
LEFT OUTER JOIN (
SELECT   sales_return_details.product_id,  

IFNULL(SUM(IFNULL( sales_return_details.return_quantity ,0)),0) AS sales_SRe   ,
 IFNULL((SUM(IFNULL(sales_return_details.return_quantity,0) * IFNULL(sales_return_details.unit_price,0)))
 /NULLIF(SUM(IFNULL( sales_return_details.return_quantity,0)),0),0) U_Price_sales_SRe  ,
 
 
IFNULL(SUM(IFNULL( sales_return_details.return_quantity ,0)),0) *
 IFNULL((SUM(IFNULL(sales_return_details.return_quantity,0) * IFNULL(sales_return_details.unit_price,0)))
 /NULLIF(SUM(IFNULL( sales_return_details.return_quantity,0)),0),0) Amount_sales_SRe  
 

FROM         sales_invoice_info LEFT OUTER JOIN
sales_details ON sales_invoice_info.sales_invoice_id = sales_details.sales_invoice_id LEFT OUTER JOIN
sales_return_details ON sales_invoice_info.sales_invoice_id = sales_return_details.sales_invoice_id 
  AND sales_details.sales_details_id = sales_return_details.sales_details_id 
 


WHERE   sales_details.is_active='Y' AND sales_details.is_delete='N' AND  
IFNULL(sales_return_details.is_active,'Y')='Y' AND IFNULL(sales_return_details.is_delete,'N')='N' 
AND sales_invoice_info.invoice_date < '2020-01-08'
     

GROUP BY   sales_return_details.product_id) Ret_Sales ON   BaseT.product_id= Ret_Sales.product_id  LEFT  OUTER JOIN  


/*END ReturnSales*/
 
 (
SELECT   
        sr.product_id,  SUM(sr.return_quantity)  Srt_quantity
      , SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0) AS Srt_UPrice       
     ,(SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0))*(SUM(sr.return_quantity) )  Srt_Amount
       
  FROM sales_return sr   
       
       WHERE  sr.is_active='Y' AND sr.is_delete='N'    
       AND sr.return_date < '2020-01-08'  
         
       
       GROUP BY sr.product_id
       
      
       ) sales_Ret ON  BaseT.product_id= sales_Ret.product_id LEFT  OUTER JOIN  
       
       (
SELECT   
       iad.product_id,   
       SUM(iad.in_qty)  INV_in_qty,  
       SUM(iad.out_qty)  INV_out_qty, 
       
        SUM(iad.in_qty * iad.unit_price) / NULLIF(SUM(iad.in_qty),0) AS INV_IN_UPrice  ,     
        SUM(iad.out_qty * iad.unit_price) / NULLIF(SUM(iad.out_qty),0) AS INV_OUT_UPrice ,  
      
          
      (SUM(iad.in_qty * iad.unit_price) / NULLIF(SUM(iad.in_qty),0))*(SUM(iad.in_qty))  INV_IN_Amount,
      (SUM(iad.out_qty * iad.unit_price) / NULLIF(SUM(iad.out_qty),0))*( SUM(iad.out_qty))  INV_OUT_Amount 
       
  FROM inventory_adjustment_details iad  LEFT OUTER JOIN
       
       inventory_adjustment_info iai ON iad.inv_adjustment_info_id=iai.id
       
       WHERE   iad.is_active='Y' AND iad.is_delete='N'  
 AND iai.date < '2020-01-08'  
       
       GROUP BY iad.product_id     
        ) Inv_Adj ON Inv_Adj.product_id=BaseT.product_id 
       
        ) Opening_All     
 ON Opening_All.product_id=BaseT.product_id  LEFT  OUTER JOIN
 
 /*Purchase_In*/
                     
(SELECT    
purchase_details.product_id,SUM(IFNULL( purchase_details.quantity,0)) AS quantity_P, 
IFNULL((SUM(IFNULL(purchase_details.quantity*purchase_details. unit_price,0)))/
NULLIF(SUM(IFNULL(purchase_details.quantity,0)),0),0) U_Price_Purchase_In,

IFNULL((SUM(IFNULL( purchase_details.quantity,0)))*( 
IFNULL((SUM(IFNULL(purchase_details.quantity*purchase_details. unit_price,0)))/
NULLIF(SUM(IFNULL(purchase_details.quantity,0)),0),0)),0) Amount_Purchase_In 
  

FROM         purchase_invoice_info LEFT OUTER JOIN
purchase_details ON purchase_invoice_info.purchase_invoice_id = purchase_details.purchase_invoice_id  
WHERE   purchase_details.is_active='Y' AND purchase_details.is_delete='N'  


AND purchase_invoice_info.invoice_date  >= '2020-01-08'
 AND purchase_invoice_info.invoice_date  <= '2020-01-22'  
 
 
GROUP BY   purchase_details.product_id  ) Purchase_In ON  BaseT.product_id= Purchase_In.product_id  Left outer JOIN
/*END Purchase_In*/

/*Opening*/

(SELECT    
purchase_details.product_id,
SUM(IFNULL( purchase_details.quantity,0)) AS quantity_Open, 
 IFNULL((SUM(IFNULL(purchase_details.quantity,0) * IFNULL(purchase_details.unit_price,0)))/
 NULLIF(SUM(IFNULL( purchase_details.quantity,0)),0),0) U_Price_opening  ,
 
 IFNULL((SUM(IFNULL( purchase_details.quantity,0))) *  (
 IFNULL((SUM(IFNULL(purchase_details.quantity,0) * IFNULL(purchase_details.unit_price,0)))/
 NULLIF(SUM(IFNULL( purchase_details.quantity,0)),0),0)),0) Amount_opening

   

FROM         purchase_invoice_info LEFT OUTER JOIN
purchase_details ON purchase_invoice_info.purchase_invoice_id = purchase_details.purchase_invoice_id 
 
WHERE   purchase_details.is_active='Y' AND purchase_details.is_delete='N' 


AND purchase_invoice_info.invoice_date  >= '2020-01-08'
 AND purchase_invoice_info.invoice_date  <= '2020-01-22'  
 
    
 GROUP BY   purchase_details.product_id  ) Opening 
 ON Opening.product_id=BaseT.product_id  LEFT  OUTER JOIN
 
/*END Opening*/

/*sales_out*/

(SELECT    
sales_details.product_id,SUM(IFNULL( sales_details.quantity,0)) AS sales_out_Qty, 

IFNULL((SUM(IFNULL(sales_details.quantity,0) * IFNULL(sales_details. unit_price,0)))/
NULLIF(SUM(IFNULL( sales_details.quantity,0)),0),0) U_Price_sales_out  ,

IFNULL((SUM(IFNULL( sales_details.quantity,0))) * (

IFNULL((SUM(IFNULL(sales_details.quantity,0) * IFNULL(sales_details. unit_price,0)))/
NULLIF(SUM(IFNULL( sales_details.quantity,0)),0),0)),0) Amount_sales_out 


FROM         sales_invoice_info LEFT OUTER JOIN
sales_details ON sales_invoice_info.sales_invoice_id = sales_details.sales_invoice_id 

WHERE   sales_details.is_active='Y' AND sales_details.is_delete='N'  

AND sales_invoice_info.invoice_date  >= '2020-01-08'
 AND sales_invoice_info.invoice_date  <= '2020-01-22'  
 
 
  
 

GROUP BY   sales_details.product_id  ) sales_out ON BaseT.product_id=sales_out.product_id 
/*END sales_out*/

/*ReturnPurchase*/
LEFT OUTER JOIN (
SELECT    
purchase_return_details.product_id , 

IFNULL(SUM( purchase_return_details.return_quantity ),0) AS quantity_PRe  , 
 IFNULL((SUM(purchase_return_details.return_quantity*purchase_return_details.unit_price))
 /  NULLIF(SUM(IFNULL( purchase_return_details.return_quantity,0)),0)      ,0) U_Price_PRe,
 
 IFNULL((IFNULL(SUM( purchase_return_details.return_quantity ),0)) * (
 IFNULL((SUM(purchase_return_details.return_quantity*purchase_return_details.unit_price))
 /  NULLIF(SUM(IFNULL( purchase_return_details.return_quantity,0)),0),0)),0)  Amount_PRe
 
 

FROM         purchase_invoice_info LEFT OUTER JOIN
purchase_details ON purchase_invoice_info.purchase_invoice_id = purchase_details.purchase_invoice_id LEFT OUTER JOIN
purchase_return_details ON purchase_invoice_info.purchase_invoice_id = purchase_return_details.purchase_invoice_id 
  AND
purchase_details.purchase_details_id = purchase_return_details.purchase_details_id 
 


WHERE   purchase_details.is_active='Y' AND purchase_details.is_delete='N' AND   
IFNULL(purchase_return_details.is_active,'Y')='Y' AND IFNULL(purchase_return_details.is_delete,'N')='N' 


AND  purchase_invoice_info.invoice_date  >= '2020-01-08'
 AND  purchase_invoice_info.invoice_date <= '2020-01-22'  
 
 
  

GROUP BY   purchase_return_details.product_id) Ret_Purchase ON   BaseT.product_id= Ret_Purchase.product_id 
/*END ReturnPurchase*/

/*ReturnOpening*/
LEFT OUTER JOIN (
SELECT    
purchase_return_details.product_id , 

IFNULL(SUM(IFNULL( purchase_return_details.return_quantity ,0)),0) AS quantity_OpenRe,

 IFNULL((SUM(IFNULL(purchase_return_details.return_quantity,0) * IFNULL(purchase_return_details.unit_price,0)))
 / NULLIF(SUM(IFNULL(purchase_return_details.return_quantity,0)),0),0) U_Price_openingRe ,
 
 IFNULL( (SUM(IFNULL( purchase_return_details.return_quantity ,0))) *( 

  SUM(IFNULL(purchase_return_details.return_quantity,0)  * IFNULL(purchase_return_details.unit_price,0)) 
 / NULLIF(SUM(IFNULL(purchase_return_details.return_quantity,0)),0)),0)  Amount_openingRe
  

FROM         purchase_invoice_info LEFT OUTER JOIN
purchase_details ON purchase_invoice_info.purchase_invoice_id = purchase_details.purchase_invoice_id LEFT OUTER JOIN
purchase_return_details ON purchase_invoice_info.purchase_invoice_id = purchase_return_details.purchase_invoice_id 
 
 AND
purchase_details.purchase_details_id = purchase_return_details.purchase_details_id 
 

WHERE   purchase_details.is_active='Y' AND purchase_details.is_delete='N' AND   
IFNULL(purchase_return_details.is_active,'Y')='Y' AND IFNULL(purchase_return_details.is_delete,'N')='N' 

AND  purchase_invoice_info.invoice_date  >= '2020-01-08'
 AND  purchase_invoice_info.invoice_date  <= '2020-01-22'  
 
 
  

GROUP BY   purchase_return_details.product_id) Ret_Opening ON   BaseT.product_id= Ret_Opening.product_id
/*END ReturnOpening*/

/*ReturnSales*/
LEFT OUTER JOIN (
SELECT   sales_return_details.product_id,  

IFNULL(SUM(IFNULL( sales_return_details.return_quantity ,0)),0) AS sales_SRe   ,
 IFNULL((SUM(IFNULL(sales_return_details.return_quantity,0) * IFNULL(sales_return_details.unit_price,0)))
 /NULLIF(SUM(IFNULL( sales_return_details.return_quantity,0)),0),0) U_Price_sales_SRe  ,
 
 
IFNULL(SUM(IFNULL( sales_return_details.return_quantity ,0)),0) *
 IFNULL((SUM(IFNULL(sales_return_details.return_quantity,0) * IFNULL(sales_return_details.unit_price,0)))
 /NULLIF(SUM(IFNULL( sales_return_details.return_quantity,0)),0),0) Amount_sales_SRe  
 

FROM         sales_invoice_info LEFT OUTER JOIN
sales_details ON sales_invoice_info.sales_invoice_id = sales_details.sales_invoice_id LEFT OUTER JOIN
sales_return_details ON sales_invoice_info.sales_invoice_id = sales_return_details.sales_invoice_id 
  AND sales_details.sales_details_id = sales_return_details.sales_details_id 
 


WHERE   sales_details.is_active='Y' AND sales_details.is_delete='N' AND  
IFNULL(sales_return_details.is_active,'Y')='Y' AND IFNULL(sales_return_details.is_delete,'N')='N' 
AND  sales_invoice_info.invoice_date  >= '2020-01-08'
 AND  sales_invoice_info.invoice_date  <= '2020-01-22'  
 
     

GROUP BY   sales_return_details.product_id) Ret_Sales ON   BaseT.product_id= Ret_Sales.product_id  LEFT  OUTER JOIN  


/*END ReturnSales*/
 
 (
SELECT   
        sr.product_id,  SUM(sr.return_quantity)  Srt_quantity
      , SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0) AS Srt_UPrice       
     ,(SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0))*(SUM(sr.return_quantity) )  Srt_Amount
       
  FROM sales_return sr   
       
       WHERE  sr.is_active='Y' AND sr.is_delete='N'    
        
         AND sr.return_date  >= '2020-01-08'
 AND sr.return_date  <= '2020-01-22'  
       
       GROUP BY sr.product_id
       
      
       ) sales_Ret ON  BaseT.product_id= sales_Ret.product_id LEFT  OUTER JOIN  
       
       (
SELECT   
       iad.product_id,   
       SUM(iad.in_qty)  INV_in_qty,  
       SUM(iad.out_qty)  INV_out_qty, 
       
        SUM(iad.in_qty * iad.unit_price) / NULLIF(SUM(iad.in_qty),0) AS INV_IN_UPrice  ,     
        SUM(iad.out_qty * iad.unit_price) / NULLIF(SUM(iad.out_qty),0) AS INV_OUT_UPrice ,  
      
          
      (SUM(iad.in_qty * iad.unit_price) / NULLIF(SUM(iad.in_qty),0))*(SUM(iad.in_qty))  INV_IN_Amount,
      (SUM(iad.out_qty * iad.unit_price) / NULLIF(SUM(iad.out_qty),0))*( SUM(iad.out_qty))  INV_OUT_Amount 
       
  FROM inventory_adjustment_details iad  LEFT OUTER JOIN
       
       inventory_adjustment_info iai ON iad.inv_adjustment_info_id=iai.id
       
       WHERE   iad.is_active='Y' AND iad.is_delete='N'  
 AND iai.date >= '2020-01-08'
AND iai.date <= '2020-01-22'  
       
       GROUP BY iad.product_id     
        ) Inv_Adj ON Inv_Adj.product_id=BaseT.product_id 
        
        )
         Tol
       
        
            ";
	}
}

// ------------------------------------------------------------------------
/* End of file language_helper.php */
/* Location: ./system/helpers/language_helper.php */