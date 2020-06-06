<?php
/**
 * Created by PhpStorm.
 * User: Nahid
 * Date: 4/22/2020
 * Time: 9:28 AM
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class InventoryStockReport_Model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }
    public function stock_report($productCatagory,$productBrand,$productId,$startDate,$endDate){
        $Catagory=" AND 1=1";
        $Brand=" AND 1=1";
        $product=" AND 1=1";
        if($productCatagory !='All'){
            $Catagory=" AND AllOPSC.category_id=".$productCatagory;
        }
        if($productBrand !='All'){
            $Brand=" AND AllOPSC.brand_id=".$productBrand;
        }if($productId !='All'){
            $product=" AND AllOPSC.product_id=".$productId;
        }



        $query2="SELECT  product_id,  productName,  category_id, title, unit_id,  unitTtile , brand_id, brandName,
/*Opening*/
IFNULL(IFNULL(quantity_Open,0) - IFNULL(quantity_OpenRe,0),0) Opening_Qty,
IFNULL(((IFNULL(quantity_Open,0) * IFNULL(U_Price_opening,0)) - (IFNULL(quantity_OpenRe,0) * IFNULL(U_Price_openingRe,0)))
/ NULLIF((IFNULL(quantity_Open,0) - IFNULL(quantity_OpenRe,0)),0),0 ) Opening_U_Price,

 IFNULL((IFNULL(IFNULL(quantity_Open,0) - IFNULL(quantity_OpenRe,0),0)) *
IFNULL(((IFNULL(quantity_Open,0) * IFNULL(U_Price_opening,0)) - (IFNULL(quantity_OpenRe,0) * IFNULL(U_Price_openingRe,0)))
/ NULLIF((IFNULL(quantity_Open,0) - IFNULL(quantity_OpenRe,0)),0),0 ),0) Opening_Amonut,
/*END Opening*/

/*--Purchase_In*/
IFNULL(IFNULL(quantity_P,0) - IFNULL(quantity_PRe,0),0) Purchase_Qty,
IFNULL(((IFNULL(quantity_P,0) * IFNULL(U_Price_Purchase_In,0)) - (IFNULL(quantity_PRe,0) * IFNULL(U_Price_PRe,0)))
/ NULLIF((IFNULL(quantity_P,0) - IFNULL(quantity_PRe,0)),0),0 ) Purchase_U_Price,

 IFNULL((IFNULL(IFNULL(quantity_P,0) - IFNULL(quantity_PRe,0),0)) *
IFNULL(((IFNULL(quantity_P,0) * IFNULL(U_Price_Purchase_In,0)) - (IFNULL(quantity_PRe,0) * IFNULL(U_Price_PRe,0)))
/ NULLIF((IFNULL(quantity_P,0) - IFNULL(quantity_PRe,0)),0),0 ),0) Purchase_Amount,
/*END Purchase_In*/


/*Sales_Out*/
IFNULL(IFNULL(sales_P,0) + IFNULL(sales_SRe,0),0) Sales_Qty,

IFNULL(((IFNULL(sales_P,0) * IFNULL(U_Price_sales_out,0)) + (IFNULL(sales_SRe,0) * IFNULL(U_Price_sales_SRe,0)))
/ NULLIF((IFNULL(sales_P,0) + IFNULL(sales_SRe,0)),0),0 ) Sales_U_Price,

 IFNULL((IFNULL(IFNULL(sales_P,0) + IFNULL(sales_SRe,0),0)) *
IFNULL(((IFNULL(sales_P,0) * IFNULL(U_Price_sales_out,0)) + (IFNULL(sales_SRe,0) * IFNULL(U_Price_sales_SRe,0)))
/ NULLIF((IFNULL(sales_P,0) + IFNULL(sales_SRe,0)),0),0 ),0) Sales_Amount,
/*END Sales_Out*/
/*Closing*/
IFNULL((Closing_Qty+Closing_QtyRe),0) Closing_Qty,

IFNULL(((Closing_Qty*Closing_U_price)+(Closing_QtyRe*Closing_U_price_Re))/  NULLIF((Closing_Qty+Closing_QtyRe),0),0) Closing_U_Price,
IFNULL((Closing_Qty+Closing_QtyRe)* (((Closing_Qty*Closing_U_price)+(Closing_QtyRe*Closing_U_price_Re))/  NULLIF((Closing_Qty+Closing_QtyRe),0)),0) Closing_Amount
/*END Closing*/
FROM

(

SELECT
 BaseT.product_id, BaseT.productName, BaseT.category_id,productcategory.title,BaseT.unit_id, unit.unitTtile ,BaseT.brand_id,brand.brandName,
/*Opening*/
IFNULL(Opening.quantity_Open,0) quantity_Open ,
IFNULL(Opening.U_Price_opening,0) U_Price_opening,
IFNULL(IFNULL(Opening.quantity_Open,0)*
IFNULL(Opening.U_Price_opening,0),0) Amount_opening,

IFNULL(Ret_Opening.quantity_OpenRe,0) quantity_OpenRe ,
IFNULL(Ret_Opening.U_Price_openingRe,0) U_Price_openingRe,
IFNULL(IFNULL(Ret_Opening.quantity_OpenRe,0)*
IFNULL(Ret_Opening.U_Price_openingRe,0),0) Amount_U_Price_openingRe,
/*END Opening*/

/*Purchase_In*/
 
IFNULL(Purchase_In.quantity_P,0) quantity_P,

IFNULL(Purchase_In.U_Price_Purchase_In,0) U_Price_Purchase_In,

IFNULL(IFNULL(Purchase_In.quantity_P,0) *
IFNULL(Purchase_In.U_Price_Purchase_In,0),0) Amount_Purchase_In ,

IFNULL(Ret_Purchase.quantity_PRe ,0) quantity_PRe,
IFNULL(Ret_Purchase.U_Price_PRe,0) U_Price_PRe,

IFNULL(IFNULL(Ret_Purchase.quantity_PRe,0) *
IFNULL(Ret_Purchase.U_Price_PRe,0),0) Amount_Purchase_In_Re  ,

/*END Purchase_In*/

/*sales_out*/
 
IFNULL(sales_out.sales_P,0) sales_P , 
IFNULL(sales_out.U_Price_sales_out,0) U_Price_sales_out,

IFNULL(IFNULL(sales_out.sales_P,0) *
IFNULL(sales_out.U_Price_sales_out,0),0) Amount_sales_out,


IFNULL(Ret_Sales.sales_SRe ,0) sales_SRe ,
IFNULL(Ret_Sales.U_Price_sales_SRe,0) U_Price_sales_SRe,

IFNULL(IFNULL(Ret_Sales.sales_SRe ,0) *
IFNULL(Ret_Sales.U_Price_sales_SRe,0),0) Amount_sales_SRe

/*END sales_out*/

/*Closing*/
, IFNULL((IFNULL(Opening.quantity_Open,0)+IFNULL(Purchase_In.quantity_P,0)-IFNULL(sales_out.sales_P,0)),0) AS Closing_Qty

,IFNULL( (((IFNULL(Opening.quantity_Open,0)*
IFNULL(Opening.U_Price_opening,0))+(IFNULL(Purchase_In.quantity_P,0) *
IFNULL(Purchase_In.U_Price_Purchase_In,0) )-(IFNULL(sales_out.sales_P,0) *
IFNULL(sales_out.U_Price_sales_out,0))) )/ 
NULLIF((IFNULL((IFNULL(Opening.quantity_Open,0)+IFNULL(Purchase_In.quantity_P,0)-IFNULL(sales_out.sales_P,0)),0)),0),0) AS Closing_U_price
,
IFNULL(((IFNULL(Opening.quantity_Open,0)*
IFNULL(Opening.U_Price_opening,0))+(IFNULL(Purchase_In.quantity_P,0) *
IFNULL(Purchase_In.U_Price_Purchase_In,0) )-(IFNULL(sales_out.sales_P,0) *
IFNULL(sales_out.U_Price_sales_out,0))),0)  Amount_Closing


,IFNULL((IFNULL(Ret_Opening.quantity_OpenRe,0)+IFNULL(Ret_Purchase.quantity_PRe,0)-IFNULL(Ret_Sales.sales_SRe,0)),0) AS Closing_QtyRe


,IFNULL( IFNULL( (IFNULL(Ret_Opening.quantity_OpenRe,0) * IFNULL(Ret_Opening.U_Price_openingRe,0)) + 
(IFNULL(Ret_Purchase.quantity_PRe,0) * IFNULL(Ret_Purchase.U_Price_PRe,0) )-
(IFNULL(Ret_Sales.sales_SRe,0) * IFNULL(Ret_Sales.U_Price_sales_SRe,0)),0) / 
NULLIF((IFNULL(Ret_Opening.quantity_OpenRe,0)+IFNULL(Ret_Purchase.quantity_PRe,0)-IFNULL(Ret_Sales.sales_SRe,0)),0),0) AS Closing_U_price_Re

,
IFNULL(((IFNULL(Ret_Opening.quantity_OpenRe,0)*
IFNULL(Ret_Opening.U_Price_openingRe,0))+(IFNULL(Ret_Opening.quantity_OpenRe,0) *
IFNULL(Ret_Purchase.U_Price_PRe,0) )-(IFNULL(Ret_Sales.sales_SRe,0) *
IFNULL(Ret_Sales.U_Price_sales_SRe,0))),0)  Amount_Closing_Re


/*END Closing*/
 FROM 
 (
SELECT product.product_id, product.productName,product.category_id ,product.unit_id,product.brand_id
  FROM  product LEFT OUTER JOIN 
 (SELECT product_id  FROM   purchase_details
  union  
  SELECT product_id FROM   purchase_return_details  
  union  
  SELECT product_id FROM   inventory_adjustment_details
  )  T2 ON product.product_id=T2.product_id  WHERE product.category_id<>1) BaseT LEFT OUTER JOIN
   
  
                      productcategory ON BaseT.category_id = productcategory.category_id LEFT OUTER JOIN
                      unit ON BaseT.unit_id = unit.unit_id LEFT OUTER JOIN
                      brand ON BaseT.brand_id = brand.brandId LEFT OUTER JOIN
                      
 /*Purchase_In*/
                     
(SELECT    
purchase_details.product_id,SUM(IFNULL( purchase_details.quantity,0)) AS quantity_P, 
IFNULL((SUM(IFNULL(purchase_details.quantity*purchase_details. unit_price,0)))/
NULLIF(SUM(IFNULL( purchase_details.quantity,0)),0),0) U_Price_Purchase_In  

FROM         purchase_invoice_info LEFT OUTER JOIN
purchase_details ON purchase_invoice_info.purchase_invoice_id = purchase_details.purchase_invoice_id  
WHERE   purchase_details.is_active='Y' AND purchase_details.is_delete='N'  
 AND purchase_invoice_info.invoice_date >= '".$startDate."'
AND purchase_invoice_info.invoice_date <= '".$endDate."'  
AND purchase_invoice_info.is_opening=0

GROUP BY   purchase_details.product_id  ) Purchase_In ON  BaseT.product_id= Purchase_In.product_id  Left outer JOIN
/*END Purchase_In*/

/*Opening*/

(SELECT    
purchase_details.product_id,
SUM(IFNULL( purchase_details.quantity,0)) AS quantity_Open, 
 IFNULL((SUM(IFNULL(purchase_details.quantity,0) * IFNULL(purchase_details.unit_price,0)))/
 NULLIF(SUM(IFNULL( purchase_details.quantity,0)),0),0) U_Price_opening /*--,*/

   

FROM         purchase_invoice_info LEFT OUTER JOIN
purchase_details ON purchase_invoice_info.purchase_invoice_id = purchase_details.purchase_invoice_id /*--LEFT OUTER JOIN*/
 
WHERE   purchase_details.is_active='Y' AND purchase_details.is_delete='N'  
  AND purchase_invoice_info.invoice_date < '".$startDate."'  
 GROUP BY   purchase_details.product_id  ) Opening 
 ON Opening.product_id=BaseT.product_id  LEFT  OUTER JOIN
 
/*END Opening*/

/*sales_out*/

(SELECT    
sales_details.product_id,SUM(IFNULL( sales_details.quantity,0)) AS sales_P, 

IFNULL((SUM(IFNULL(sales_details.quantity,0) * IFNULL(sales_details. unit_price,0)))/
NULLIF(SUM(IFNULL( sales_details.quantity,0)),0),0) U_Price_sales_out /* --,*/


FROM         sales_invoice_info LEFT OUTER JOIN
sales_details ON sales_invoice_info.sales_invoice_id = sales_details.sales_invoice_id LEFT OUTER JOIN
sales_return_details ON sales_invoice_info.sales_invoice_id = sales_return_details.sales_invoice_id  
 

WHERE   sales_details.is_active='Y' AND sales_details.is_delete='N'  
 AND sales_invoice_info.invoice_date >= '".$startDate."'
AND sales_invoice_info.invoice_date <= '".$endDate."'
 

GROUP BY   sales_details.product_id  ) sales_out ON BaseT.product_id=sales_out.product_id 
/*END sales_out*/

/*ReturnPurchase*/
LEFT OUTER JOIN (
SELECT    
purchase_return_details.product_id , 

IFNULL(SUM( purchase_return_details.return_quantity ),0) AS quantity_PRe  , 
 IFNULL((SUM(purchase_return_details.return_quantity*purchase_return_details.unit_price))
 /  NULLIF(SUM(IFNULL( purchase_return_details.return_quantity,0)),0)      ,0) U_Price_PRe

FROM         purchase_invoice_info LEFT OUTER JOIN
purchase_details ON purchase_invoice_info.purchase_invoice_id = purchase_details.purchase_invoice_id LEFT OUTER JOIN
purchase_return_details ON purchase_invoice_info.purchase_invoice_id = purchase_return_details.purchase_invoice_id 
  AND
purchase_details.purchase_details_id = purchase_return_details.purchase_details_id 
 


WHERE   purchase_details.is_active='Y' AND purchase_details.is_delete='N' AND   
IFNULL(purchase_return_details.is_active,'Y')='Y' AND IFNULL(purchase_return_details.is_delete,'N')='N' 
AND purchase_invoice_info.invoice_date >= '".$startDate."'
AND purchase_invoice_info.invoice_date <= '".$endDate."'  AND purchase_details.is_opening=0

GROUP BY   purchase_return_details.product_id) Ret_Purchase ON   BaseT.product_id= Ret_Purchase.product_id 
/*END ReturnPurchase*/

/*ReturnOpening*/
LEFT OUTER JOIN (
SELECT    
purchase_return_details.product_id , 

IFNULL(SUM(IFNULL( purchase_return_details.return_quantity ,0)),0) AS quantity_OpenRe,

 IFNULL((SUM(IFNULL(purchase_return_details.return_quantity,0) * IFNULL(purchase_return_details.unit_price,0)))
 / NULLIF(SUM(IFNULL(purchase_return_details.return_quantity,0)),0),0) U_Price_openingRe 
  

FROM         purchase_invoice_info LEFT OUTER JOIN
purchase_details ON purchase_invoice_info.purchase_invoice_id = purchase_details.purchase_invoice_id LEFT OUTER JOIN
purchase_return_details ON purchase_invoice_info.purchase_invoice_id = purchase_return_details.purchase_invoice_id 
 
 AND
purchase_details.purchase_details_id = purchase_return_details.purchase_details_id 
 

WHERE   purchase_details.is_active='Y' AND purchase_details.is_delete='N' AND   
IFNULL(purchase_return_details.is_active,'Y')='Y' AND IFNULL(purchase_return_details.is_delete,'N')='N' 
AND purchase_invoice_info.invoice_date < '".$startDate."'
  

GROUP BY   purchase_return_details.product_id) Ret_Opening ON   BaseT.product_id= Ret_Opening.product_id
/*END ReturnOpening*/

/*ReturnSales*/
LEFT OUTER JOIN (
SELECT   sales_return_details.product_id,  

IFNULL(SUM(IFNULL( sales_return_details.return_quantity ,0)),0) AS sales_SRe   ,
 IFNULL((SUM(IFNULL(sales_return_details.return_quantity,0) * IFNULL(sales_return_details.unit_price,0)))
 /NULLIF(SUM(IFNULL( sales_return_details.return_quantity,0)),0),0) U_Price_sales_SRe  

FROM         sales_invoice_info LEFT OUTER JOIN
sales_details ON sales_invoice_info.sales_invoice_id = sales_details.sales_invoice_id LEFT OUTER JOIN
sales_return_details ON sales_invoice_info.sales_invoice_id = sales_return_details.sales_invoice_id 
  AND sales_details.sales_details_id = sales_return_details.sales_details_id 
 


WHERE   sales_details.is_active='Y' AND sales_details.is_delete='N' AND  /*--purchase_invoice_info.branch_id= AND */ 
IFNULL(sales_return_details.is_active,'Y')='Y' AND IFNULL(sales_return_details.is_delete,'N')='N' 
AND sales_invoice_info.invoice_date >= '".$startDate."'
AND sales_invoice_info.invoice_date <= '".$endDate."'    

GROUP BY   sales_return_details.product_id) Ret_Sales ON   BaseT.product_id= Ret_Sales.product_id 
/*END ReturnSales*/

) AllOPSC  WHERE 1=1  "." ".$Catagory." ".$Brand." ".$product;

        $query2.=" AND (  Closing_Qty >0) ";
        $query2.=" ORDER BY title,brandName,productName";





        $query3="SELECT 
product_id,category_id,unit_id,brand_id, brandName BrandName,productName,CategoryName,unitTtile,
IFNULL(OP_quantity,0) OP_quantity,IFNULL(OP_UPrice,0) OP_UPrice ,IFNULL(OP_Amount,0) OP_Amount,
IFNULL(Pur_quantity,0) Pur_quantity,IFNULL(Pur_UPrice,0) Pur_UPrice,IFNULL(Pur_Amount,0) Pur_Amount,
IFNULL(S_quantity,0) S_quantity,IFNULL(S_UPrice,0) S_UPrice ,IFNULL(S_Amount,0) S_Amount,

IFNULL(IFNULL(OP_quantity,0)+IFNULL(Pur_quantity,0)-IFNULL(S_quantity,0),0) C_quantity ,

IFNULL(IFNULL((IFNULL(OP_Amount,0)+IFNULL(Pur_Amount,0)) ,0)/NULLIF(((IFNULL(OP_quantity,0)+IFNULL(Pur_quantity,0))),0),0) C_UPrice,
 
 IFNULL(((IFNULL(IFNULL(OP_quantity,0)+IFNULL(Pur_quantity,0)-IFNULL(S_quantity,0),0))*
 (IFNULL(IFNULL((IFNULL(OP_Amount,0)+IFNULL(Pur_Amount,0)) ,0)/NULLIF(((IFNULL(OP_quantity,0)+IFNULL(Pur_quantity,0))),0),0))),0) C_Amount

FROM (
SELECT BaseT.product_id,BaseT.category_id,BaseT.unit_id,BaseT.brand_id, brandName BrandName,BaseT.productName, title CategoryName,unitTtile,
IFNULL(OP_quantity,0) OP_quantity,IFNULL(OP_UPrice,0) OP_UPrice ,IFNULL(OP_Amount,0) OP_Amount,

IFNULL((IFNULL(Pur_quantity,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0)),0) Pur_quantity ,

IFNULL(((IFNULL((IFNULL(Pur_Amount,0)+ IFNULL(Srt_Amount,0) + IFNULL(INV_IN_Amount,0) ),0))/
IFNULL((IFNULL((IFNULL(Pur_quantity,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0) ),0)),0)),0)  Pur_UPrice,

 
IFNULL((IFNULL(Pur_Amount,0)+ IFNULL(Srt_Amount,0) + IFNULL(INV_IN_Amount,0)),0) Pur_Amount,

 
IFNULL((IFNULL(S_quantity,0) + IFNULL(INV_out_qty,0)),0) S_quantity ,
 
IFNULL(((IFNULL((IFNULL(S_Amount,0) + IFNULL(INV_OUT_Amount,0)),0))

/NULLIF((IFNULL((IFNULL(S_quantity,0) + IFNULL(INV_out_qty,0)),0)),0)),0) S_UPrice,
IFNULL((IFNULL(S_Amount,0) + IFNULL(INV_OUT_Amount,0)),0) S_Amount 
 

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
  ON product.product_id=T2.product_id  WHERE product.category_id<>1) BaseT LEFT OUTER JOIN
   
  
                      productcategory ON BaseT.category_id = productcategory.category_id LEFT OUTER JOIN
                      unit ON BaseT.unit_id = unit.unit_id LEFT OUTER JOIN
                      brand ON BaseT.brand_id = brand.brandId LEFT OUTER JOIN
                      
 (
SELECT   
       pd.product_id  ,  SUM(pd.quantity)  OP_quantity
      , SUM(pd.quantity * pd.unit_price) / NULLIF(SUM(pd.quantity),0) AS OP_UPrice       
     ,(SUM(pd.quantity * pd.unit_price) / NULLIF(SUM(pd.quantity),0))*(SUM(pd.quantity) )  OP_Amount
       
  FROM purchase_details pd  LEFT OUTER JOIN
       
       purchase_invoice_info pii ON pd.purchase_invoice_id=pii.purchase_invoice_id
       
       WHERE  pd.is_active='Y' AND pd.is_delete='N' AND pii.invoice_date < '".$startDate."'  
       
       GROUP BY pd.product_id  
       
        
       ) opening    
 ON Opening.product_id=BaseT.product_id  LEFT  OUTER JOIN
 
 (
SELECT   
       pd.product_id,   SUM(pd.quantity)  Pur_quantity
      , SUM(pd.quantity * pd.unit_price) / NULLIF(SUM(pd.quantity),0) AS Pur_UPrice       
     ,(SUM(pd.quantity * pd.unit_price) / NULLIF(SUM(pd.quantity),0))*(SUM(pd.quantity) )  Pur_Amount
       
  FROM purchase_details pd  LEFT OUTER JOIN
       
       purchase_invoice_info pii ON pd.purchase_invoice_id=pii.purchase_invoice_id
       
       WHERE  pd.is_active='Y' AND pd.is_delete='N'    
       AND pii.invoice_date >= '".$startDate."' 
       AND pii.invoice_date <= '".$endDate."'
         
       
       GROUP BY pd.product_id 
       
      
       ) purchase ON  BaseT.product_id= purchase.product_id LEFT  OUTER JOIN
       
    (
SELECT   
        sr.product_id,  SUM(sr.return_quantity)  Srt_quantity
      , SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0) AS Srt_UPrice       
     ,(SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0))*(SUM(sr.return_quantity) )  Srt_Amount
       
  FROM sales_return sr   
       
       WHERE  sr.is_active='Y' AND sr.is_delete='N'    
       AND sr.return_date >= '".$startDate."'
       AND sr.return_date <= '".$endDate."'
         
       
       GROUP BY sr.product_id
       
      
       ) sales_Ret ON  BaseT.product_id= sales_Ret.product_id LEFT  OUTER JOIN   
       (
SELECT   
       sd.product_id,   SUM(sd.quantity)  S_quantity
      , SUM(sd.quantity * sd.unit_price) / NULLIF(SUM(sd.quantity),0) AS S_UPrice       
     ,(SUM(sd.quantity * sd.unit_price) / NULLIF(SUM(sd.quantity),0))*(SUM(sd.quantity) )  S_Amount
       
  FROM sales_details sd  LEFT OUTER JOIN
       
       sales_invoice_info sii ON sd.sales_invoice_id=sii.sales_invoice_id
       
       WHERE   sd.is_active='Y' AND sd.is_delete='N'  
 AND sii.invoice_date >= '".$startDate."'
AND sii.invoice_date <= '".$endDate."'
       
       GROUP BY sd.product_id 
       
       
        ) Sales ON Sales.product_id=BaseT.product_id 
        LEFT  OUTER JOIN   
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
 AND iai.date >= '".$startDate."'
AND iai.date <= '".$endDate."' 
       
       GROUP BY iad.product_id 
       
       
        ) Inv_Adj ON Inv_Adj.product_id=BaseT.product_id 
        
        
        
             ) Tol WHERE 1=1";



        $query4="SELECT 
 
  product_id, category_id, unit_id, brand_id,
    brandName BrandName, productName,   CategoryName, unitTtile,

IFNULL(OP_quantity,0) OP_quantity,
IFNULL(OP_UPrice,0) OP_UPrice ,
IFNULL(OP_Amount,0) OP_Amount,
IFNULL(Pur_quantity,0) Pur_quantity,
IFNULL(Pur_UPrice,0) Pur_UPrice,
IFNULL(Pur_Amount,0) Pur_Amount,
IFNULL(S_quantity,0) S_quantity,
IFNULL(S_UPrice,0) S_UPrice,
IFNULL(S_Amount,0) S_Amount,

IFNULL(IFNULL(OP_quantity,0)+IFNULL(Pur_quantity,0)-IFNULL(S_quantity,0),0) C_quantity ,

IFNULL(IFNULL((IFNULL(OP_Amount,0)+IFNULL(Pur_Amount,0)) ,0)/NULLIF(((IFNULL(OP_quantity,0)+IFNULL(Pur_quantity,0))),0),0) C_UPrice,
 

 IFNULL(((IFNULL(IFNULL(OP_quantity,0)+IFNULL(Pur_quantity,0)-IFNULL(S_quantity,0),0))*
 (IFNULL(IFNULL((IFNULL(OP_Amount,0)+IFNULL(Pur_Amount,0)) ,0)/NULLIF(((IFNULL(OP_quantity,0)+IFNULL(Pur_quantity,0))),0),0))),0) C_Amount


FROM


(SELECT BaseT.product_id,BaseT.category_id,BaseT.unit_id,BaseT.brand_id, brand.brandName BrandName,BaseT.productName, title CategoryName,unit.unitTtile,

  IFNULL(OP_quantity,0) OP_quantity,
IFNULL(OP_UPrice,0) OP_UPrice 
,IFNULL(OP_Amount,0) OP_Amount,

IFNULL((IFNULL(Pur_quantity,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0)),0) Pur_quantity ,

IFNULL(((IFNULL((IFNULL(Pur_Amount,0)+ IFNULL(Srt_Amount,0) + IFNULL(INV_IN_Amount,0) ),0))/
NULLIF((IFNULL((IFNULL(Pur_quantity,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0) ),0)),0)),0)  Pur_UPrice,



IFNULL((IFNULL(Pur_Amount,0)+ IFNULL(Srt_Amount,0)+IFNULL(INV_IN_Amount,0)),0) Pur_Amount,



IFNULL((IFNULL(S_quantity,0) + IFNULL(INV_out_qty,0)),0) S_quantity ,


IFNULL(((IFNULL((IFNULL(S_Amount,0) + IFNULL(INV_OUT_Amount,0)),0))

/NULLIF((IFNULL((IFNULL(S_quantity,0) + IFNULL(INV_out_qty,0)),0)),0)),0) S_UPrice,
IFNULL((IFNULL(S_Amount,0) + IFNULL(INV_OUT_Amount,0)),0) S_Amount 
 

FROM 
 (
SELECT product.product_id, product.productName,product.category_id ,product.unit_id,product.brand_id
  FROM  
 (SELECT product_id  FROM   purchase_details
  union  
  SELECT product_id FROM   purchase_return_details  
  union  
  SELECT product_id FROM   inventory_adjustment_details
  )   
    T2 LEFT OUTER JOIN 
   product  
  ON product.product_id=T2.product_id  WHERE product.category_id<>1) BaseT LEFT OUTER JOIN
   
  
                      productcategory ON BaseT.category_id = productcategory.category_id LEFT OUTER JOIN
                      unit ON BaseT.unit_id = unit.unit_id LEFT OUTER JOIN
                      brand ON BaseT.brand_id = brand.brandId LEFT OUTER JOIN
                      
  (SELECT BaseT.product_id, BaseT.category_id , BaseT.unit_id, BaseT.brand_id,
 brandName BrandName,productName, title CategoryName,unitTtile,

(IFNULL((IFNULL(OP_quantity,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0)),0)) -
(IFNULL((IFNULL(S_quantity,0) + IFNULL(INV_out_qty,0)),0))  
OP_quantity  ,

IFNULL((IFNULL(Pur_Amount,0)+ IFNULL(Srt_Amount,0) + IFNULL(INV_IN_Amount,0)),0) / NULLIF((
IFNULL((IFNULL(Pur_quantity,0)+ IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0)),0)),0)   
OP_UPrice ,

  IFNULL(((IFNULL((IFNULL(OP_quantity,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0)),0)) -
(IFNULL((IFNULL(S_quantity,0) + IFNULL(INV_out_qty,0)),0)))*(IFNULL((IFNULL(Pur_Amount,0)+ 
IFNULL(Srt_Amount,0) + IFNULL(INV_IN_Amount,0)),0) / NULLIF((
IFNULL((IFNULL(Pur_quantity,0)+ IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0)),0)),0)) ,0)
 OP_Amount 
  
FROM 
(
SELECT product.product_id, product.productName,product.category_id ,product.unit_id,product.brand_id
  FROM  
 (SELECT product_id  FROM   purchase_details
  union  
  SELECT product_id FROM   purchase_return_details 
   union  
  SELECT product_id FROM   inventory_adjustment_details 
  )   
    T2 LEFT OUTER JOIN 
   product  
  ON product.product_id=T2.product_id  WHERE product.category_id<>1) BaseT LEFT OUTER JOIN
     productcategory ON BaseT.category_id = productcategory.category_id LEFT OUTER JOIN
     unit ON BaseT.unit_id = unit.unit_id LEFT OUTER JOIN
     brand ON BaseT.brand_id = brand.brandId LEFT OUTER JOIN
(
 
SELECT   
       pd.product_id  ,  SUM(pd.quantity)  OP_quantity
      , SUM(pd.quantity * pd.unit_price) / NULLIF(SUM(pd.quantity),0) AS OP_UPrice       
     ,(SUM(pd.quantity * pd.unit_price) / NULLIF(SUM(pd.quantity),0))*(SUM(pd.quantity) )  OP_Amount
       
  FROM purchase_details pd  LEFT OUTER JOIN
       
       purchase_invoice_info pii ON pd.purchase_invoice_id=pii.purchase_invoice_id
       
       WHERE  pd.is_active='Y' AND pd.is_delete='N' AND pii.invoice_date < '".$startDate."'  
       
       GROUP BY pd.product_id  
       
        
       ) opening  ON opening.product_id=BaseT.product_id  LEFT  OUTER JOIN
 
 (
SELECT   
       pd.product_id,   SUM(pd.quantity)  Pur_quantity
      , SUM(pd.quantity * pd.unit_price) / NULLIF(SUM(pd.quantity),0) AS Pur_UPrice       
     ,(SUM(pd.quantity * pd.unit_price) / NULLIF(SUM(pd.quantity),0))*(SUM(pd.quantity) )  Pur_Amount
       
  FROM purchase_details pd  LEFT OUTER JOIN      
       purchase_invoice_info pii ON pd.purchase_invoice_id=pii.purchase_invoice_id       
       WHERE  pd.is_active='Y' AND pd.is_delete='N'    
       AND pii.invoice_date < '".$startDate."' 
               
       
       GROUP BY pd.product_id      
      
       ) purchase ON  BaseT.product_id= purchase.product_id LEFT  OUTER JOIN
       
    (
SELECT   
        sr.product_id,  SUM(sr.return_quantity)  Srt_quantity
      , SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0) AS Srt_UPrice       
     ,(SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0))*(SUM(sr.return_quantity) )  Srt_Amount
       
  FROM sales_return sr   
       
       WHERE  sr.is_active='Y' AND sr.is_delete='N'    
       AND sr.return_date < '".$startDate."'  
         
       
       GROUP BY sr.product_id
       
      
       ) sales_Ret ON  BaseT.product_id= sales_Ret.product_id LEFT  OUTER JOIN   
       (
SELECT   
       sd.product_id,   SUM(sd.quantity)  S_quantity
      , SUM(sd.quantity * sd.unit_price) / NULLIF(SUM(sd.quantity),0)
       AS S_UPrice       
     ,(SUM(sd.quantity * sd.unit_price) / NULLIF(SUM(sd.quantity),0))*(SUM(sd.quantity) )  S_Amount
       
  FROM sales_details sd  LEFT OUTER JOIN
       
       sales_invoice_info sii ON sd.sales_invoice_id=sii.sales_invoice_id
       
       WHERE   sd.is_active='Y' AND sd.is_delete='N'  
 AND sii.invoice_date < '".$startDate."'  
       
       GROUP BY sd.product_id 
       
       
        ) Sales ON Sales.product_id=BaseT.product_id 
        LEFT  OUTER JOIN   
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
 AND iai.date < '".$startDate."' 
       
       GROUP BY iad.product_id     
        ) Inv_Adj ON Inv_Adj.product_id=BaseT.product_id 
        
        ) Opening_All     
 ON Opening_All.product_id=BaseT.product_id  LEFT  OUTER JOIN
 
 (
SELECT   
       pd.product_id,   SUM(pd.quantity)  Pur_quantity
      , SUM(pd.quantity * pd.unit_price) / NULLIF(SUM(pd.quantity),0) AS Pur_UPrice       
     ,(SUM(pd.quantity * pd.unit_price) / NULLIF(SUM(pd.quantity),0))*(SUM(pd.quantity) )  Pur_Amount
       
  FROM purchase_details pd  LEFT OUTER JOIN      
       purchase_invoice_info pii ON pd.purchase_invoice_id=pii.purchase_invoice_id       
       WHERE  pd.is_active='Y' AND pd.is_delete='N'    
       AND pii.invoice_date >= '".$startDate."' 
       AND pii.invoice_date <= '".$endDate."'          
       
       GROUP BY pd.product_id      
      
       ) purchase ON  BaseT.product_id= purchase.product_id LEFT  OUTER JOIN
       
    (
SELECT   
        sr.product_id,  SUM(sr.return_quantity)  Srt_quantity
      , SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0) AS Srt_UPrice       
     ,(SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0))*(SUM(sr.return_quantity) )  Srt_Amount
       
  FROM sales_return sr   
       
       WHERE  sr.is_active='Y' AND sr.is_delete='N'    
       AND sr.return_date >= '".$startDate."'
       AND sr.return_date <= '".$endDate."'  
         
       
       GROUP BY sr.product_id
       
      
       ) sales_Ret ON  BaseT.product_id= sales_Ret.product_id LEFT  OUTER JOIN   
       (
SELECT   
       sd.product_id,   SUM(sd.quantity)  S_quantity
      , SUM(sd.quantity * sd.unit_price) / NULLIF(SUM(sd.quantity),0) AS S_UPrice       
     ,(SUM(sd.quantity * sd.unit_price) / NULLIF(SUM(sd.quantity),0))*(SUM(sd.quantity) )  S_Amount
       
  FROM sales_details sd  LEFT OUTER JOIN
       
       sales_invoice_info sii ON sd.sales_invoice_id=sii.sales_invoice_id
       
       WHERE   sd.is_active='Y' AND sd.is_delete='N'  
 AND sii.invoice_date >= '".$startDate."'
AND sii.invoice_date <= '".$endDate."'  
       
       GROUP BY sd.product_id 
       
       
        ) Sales ON Sales.product_id=BaseT.product_id 
        LEFT  OUTER JOIN   
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
 AND iai.date >= '".$startDate."'
AND iai.date <= '".$endDate."'   
       
       GROUP BY iad.product_id     
        ) Inv_Adj ON Inv_Adj.product_id=BaseT.product_id 
        
        
        
            ) Tol WHERE 1=1";



        if($productCatagory !='All'){
            $query4.=" AND category_id=".$productCatagory;
        }
        if($productBrand !='All'){
            $query4.=" AND brand_id=".$productBrand;
        }if($productId !='All'){
            $query4.=" AND product_id=".$productId;
        }
        log_message('error','this is stock report'.print_r($query4,true));
        $query = $this->db->query($query4);
        $result = $query->result();
        foreach ($result as $key => $value) {
            $array[$value->CategoryName ][$value->BrandName][] = $value;
        }
        return $array;

    }

    function get_empty_cylinder_with_refill_with_out_refill($productCatagory,$productBrand,$productId,$startDate,$endDate){

        $query4="SELECT 
  pp2.product_id AS empty_cylinder_id,
  
TolEmpty.product_id,TolEmpty.category_id,TolEmpty.unit_id,TolEmpty.brand_id, 
 TolEmpty.BrandName, TolEmpty.productName,TolEmpty.CategoryName, TolEmpty.unitTtile,
  IFNULL(TolEmpty.OP_quantity,0) OP_quantity,
IFNULL(TolEmpty.OP_UPrice,0) OP_UPrice ,
IFNULL(TolEmpty.OP_Amount,0) OP_Amount ,

IFNULL(TolEmpty.Pur_Qty,0) AS Pur_Qty,
IFNULL(TolEmpty.Pur_UPrice,0) AS Pur_UPrice,
IFNULL(TolEmpty.Pur_Amount,0) AS Pur_Amount,

IFNULL(TolEmpty.Sales_Qty,0) Sales_Qty,
IFNULL(TolEmpty.Sales_UPrice,0) Sales_UPrice,
IFNULL(TolEmpty.Sales_Amount,0) Sales_Amount,

IFNULL((IFNULL(TolEmpty.OP_quantity,0)+IFNULL(TolEmpty.Pur_Qty,0)-IFNULL(TolEmpty.Sales_Qty,0)),0) Closing_Qnty,

IFNULL((IFNULL(TolEmpty.OP_Amount,0)+ IFNULL(TolEmpty.Pur_Amount,0) )/ NULLIF(( IFNULL(TolEmpty.OP_quantity,0)+IFNULL(TolEmpty.Pur_Qty,0)),0)  ,0)  Closing_UPrice,

IFNULL((IFNULL((IFNULL(TolEmpty.OP_quantity,0)+IFNULL(TolEmpty.Pur_Qty,0)-IFNULL(TolEmpty.Sales_Qty,0)),0))*(IFNULL((IFNULL(TolEmpty.OP_Amount,0)+ IFNULL(TolEmpty.Pur_Amount,0) )/ NULLIF(( IFNULL(TolEmpty.OP_quantity,0)+IFNULL(TolEmpty.Pur_Qty,0)),0)  ,0) ),0) Closing_Amount,

IFNULL(Tol.OP_quantity,0) OP_quantity_refill,
IFNULL(Tol.Pur_quantity,0) Pur_quantity_refill,

IFNULL(Tol.S_quantity,0) S_quantity_refill,


IFNULL(IFNULL(Tol.OP_quantity,0)+IFNULL(Tol.Pur_quantity,0)-IFNULL(Tol.S_quantity,0),0) C_quantity_refill 
 


 



FROM


(SELECT BaseT.product_id,BaseT.category_id,BaseT.unit_id,BaseT.brand_id, brand.brandName BrandName,BaseT.productName, title CategoryName,unit.unitTtile,

  IFNULL(OP_quantity,0) OP_quantity,
IFNULL(OP_UPrice,0) OP_UPrice 
,IFNULL(OP_Amount,0) OP_Amount,

IFNULL((IFNULL(Pur_quantity,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0)),0) Pur_quantity ,

IFNULL(((IFNULL((IFNULL(Pur_Amount,0)+ IFNULL(Srt_Amount,0) + IFNULL(INV_IN_Amount,0) ),0))/
NULLIF((IFNULL((IFNULL(Pur_quantity,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0) ),0)),0)),0)  Pur_UPrice,



IFNULL((IFNULL(Pur_Amount,0)+ IFNULL(Srt_Amount,0)+IFNULL(INV_IN_Amount,0)),0) Pur_Amount,



IFNULL((IFNULL(S_quantity,0) + IFNULL(INV_out_qty,0)),0) S_quantity ,


IFNULL(((IFNULL((IFNULL(S_Amount,0) + IFNULL(INV_OUT_Amount,0)),0))

/NULLIF((IFNULL((IFNULL(S_quantity,0) + IFNULL(INV_out_qty,0)),0)),0)),0) S_UPrice,
IFNULL((IFNULL(S_Amount,0) + IFNULL(INV_OUT_Amount,0)),0) S_Amount 
 

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
  ON product.product_id=T2.product_id  WHERE product.category_id<>1) BaseT LEFT OUTER JOIN
   
  
                      productcategory ON BaseT.category_id = productcategory.category_id LEFT OUTER JOIN
                      unit ON BaseT.unit_id = unit.unit_id LEFT OUTER JOIN
                      brand ON BaseT.brand_id = brand.brandId LEFT OUTER JOIN
                      
  (SELECT BaseT.product_id, BaseT.category_id , BaseT.unit_id, BaseT.brand_id,
 brandName BrandName,productName, title CategoryName,unitTtile,

(IFNULL((IFNULL(OP_quantity,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0)),0)) -
(IFNULL((IFNULL(S_quantity,0) + IFNULL(INV_out_qty,0)),0))  
OP_quantity  ,

IFNULL((IFNULL(Pur_Amount,0)+ IFNULL(Srt_Amount,0) + IFNULL(INV_IN_Amount,0)),0) / NULLIF((
IFNULL((IFNULL(Pur_quantity,0)+ IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0)),0)),0)   
OP_UPrice ,

  IFNULL(((IFNULL((IFNULL(OP_quantity,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0)),0)) -
(IFNULL((IFNULL(S_quantity,0) + IFNULL(INV_out_qty,0)),0)))*(IFNULL((IFNULL(Pur_Amount,0)+ 
IFNULL(Srt_Amount,0) + IFNULL(INV_IN_Amount,0)),0) / NULLIF((
IFNULL((IFNULL(Pur_quantity,0)+ IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0)),0)),0)) ,0)
 OP_Amount 
  
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
  ON product.product_id=T2.product_id  WHERE product.category_id<>1) BaseT LEFT OUTER JOIN
     productcategory ON BaseT.category_id = productcategory.category_id LEFT OUTER JOIN
     unit ON BaseT.unit_id = unit.unit_id LEFT OUTER JOIN
     brand ON BaseT.brand_id = brand.brandId LEFT OUTER JOIN
(
 
SELECT   
       pd.product_id  ,  SUM(pd.quantity)  OP_quantity
      , SUM(pd.quantity * pd.unit_price) / NULLIF(SUM(pd.quantity),0) AS OP_UPrice       
     ,(SUM(pd.quantity * pd.unit_price) / NULLIF(SUM(pd.quantity),0))*(SUM(pd.quantity) )  OP_Amount
       
  FROM purchase_details pd  LEFT OUTER JOIN
       
       purchase_invoice_info pii ON pd.purchase_invoice_id=pii.purchase_invoice_id
       
       WHERE  pd.is_active='Y' AND pd.is_delete='N' AND pii.invoice_date < '".$startDate."'   
       
       GROUP BY pd.product_id  
       
        
       ) opening  ON opening.product_id=BaseT.product_id  LEFT  OUTER JOIN
 
 (
SELECT   
       pd.product_id,   SUM(pd.quantity)  Pur_quantity
      , SUM(pd.quantity * pd.unit_price) / NULLIF(SUM(pd.quantity),0) AS Pur_UPrice       
     ,(SUM(pd.quantity * pd.unit_price) / NULLIF(SUM(pd.quantity),0))*(SUM(pd.quantity) )  Pur_Amount
       
  FROM purchase_details pd  LEFT OUTER JOIN      
       purchase_invoice_info pii ON pd.purchase_invoice_id=pii.purchase_invoice_id       
       WHERE  pd.is_active='Y' AND pd.is_delete='N'    
       AND pii.invoice_date < '".$startDate."' 
               
       
       GROUP BY pd.product_id      
      
       ) purchase ON  BaseT.product_id= purchase.product_id LEFT  OUTER JOIN
       
    (
SELECT   
        sr.product_id,  SUM(sr.return_quantity)  Srt_quantity
      , SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0) AS Srt_UPrice       
     ,(SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0))*(SUM(sr.return_quantity) )  Srt_Amount
       
  FROM sales_return sr   
       
       WHERE  sr.is_active='Y' AND sr.is_delete='N'    
       AND sr.return_date < '".$startDate."' 
         
       
       GROUP BY sr.product_id
       
      
       ) sales_Ret ON  BaseT.product_id= sales_Ret.product_id LEFT  OUTER JOIN   
       (
SELECT   
       sd.product_id,   SUM(sd.quantity)  S_quantity
      , SUM(sd.quantity * sd.unit_price) / NULLIF(SUM(sd.quantity),0)
       AS S_UPrice       
     ,(SUM(sd.quantity * sd.unit_price) / NULLIF(SUM(sd.quantity),0))*(SUM(sd.quantity) )  S_Amount
       
  FROM sales_details sd  LEFT OUTER JOIN
       
       sales_invoice_info sii ON sd.sales_invoice_id=sii.sales_invoice_id
       
       WHERE   sd.is_active='Y' AND sd.is_delete='N'  
 AND sii.invoice_date < '".$startDate."'  
       
       GROUP BY sd.product_id 
       
       
        ) Sales ON Sales.product_id=BaseT.product_id 
        LEFT  OUTER JOIN   
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
 AND iai.date < '".$startDate."' 
       
       GROUP BY iad.product_id     
        ) Inv_Adj ON Inv_Adj.product_id=BaseT.product_id 
        
        ) Opening_All     
 ON Opening_All.product_id=BaseT.product_id  LEFT  OUTER JOIN
 
 (
SELECT   
       pd.product_id,   SUM(pd.quantity)  Pur_quantity
      , SUM(pd.quantity * pd.unit_price) / NULLIF(SUM(pd.quantity),0) AS Pur_UPrice       
     ,(SUM(pd.quantity * pd.unit_price) / NULLIF(SUM(pd.quantity),0))*(SUM(pd.quantity) )  Pur_Amount
       
  FROM purchase_details pd  LEFT OUTER JOIN      
       purchase_invoice_info pii ON pd.purchase_invoice_id=pii.purchase_invoice_id       
       WHERE  pd.is_active='Y' AND pd.is_delete='N'    
       AND pii.invoice_date >= '".$startDate."' 
       AND pii.invoice_date <= '".$endDate."'          
       
       GROUP BY pd.product_id      
      
       ) purchase ON  BaseT.product_id= purchase.product_id LEFT  OUTER JOIN
       
    (
SELECT   
        sr.product_id,  SUM(sr.return_quantity)  Srt_quantity
      , SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0) AS Srt_UPrice       
     ,(SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0))*(SUM(sr.return_quantity) )  Srt_Amount
       
  FROM sales_return sr   
       
       WHERE  sr.is_active='Y' AND sr.is_delete='N'    
       AND sr.return_date >= '".$startDate."'
       AND sr.return_date <= '".$endDate."' 
         
       
       GROUP BY sr.product_id
       
      
       ) sales_Ret ON  BaseT.product_id= sales_Ret.product_id LEFT  OUTER JOIN   
       (
SELECT   
       sd.product_id,   SUM(sd.quantity)  S_quantity
      , SUM(sd.quantity * sd.unit_price) / NULLIF(SUM(sd.quantity),0) AS S_UPrice       
     ,(SUM(sd.quantity * sd.unit_price) / NULLIF(SUM(sd.quantity),0))*(SUM(sd.quantity) )  S_Amount
       
  FROM sales_details sd  LEFT OUTER JOIN
       
       sales_invoice_info sii ON sd.sales_invoice_id=sii.sales_invoice_id
       
       WHERE   sd.is_active='Y' AND sd.is_delete='N'  
 AND sii.invoice_date >= '".$startDate."'
AND sii.invoice_date <= '".$endDate."' 
       
       GROUP BY sd.product_id 
       
       
        ) Sales ON Sales.product_id=BaseT.product_id 
        LEFT  OUTER JOIN   
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
 AND iai.date >= '".$startDate."'
AND iai.date <= '".$endDate."'  
       
       GROUP BY iad.product_id     
        ) Inv_Adj ON Inv_Adj.product_id=BaseT.product_id 
        
        
        
            ) Tol LEFT JOIN package_products on Tol.product_id=package_products.product_id
LEFT JOIN (
SELECT product.category_id,package_products.product_id,package_products.package_id 
FROM package_products LEFT JOIN product 
ON product.product_id=package_products.product_id 
WHERE product.category_id=1
) pp2 on pp2.package_id=package_products.package_id




WHERE 1=1";

        $query5="SELECT 
  pp2.product_id AS empty_cylinder_id,
  
TolEmpty.product_id,TolEmpty.category_id,TolEmpty.unit_id,TolEmpty.brand_id, 
 TolEmpty.BrandName, TolEmpty.productName,TolEmpty.CategoryName, TolEmpty.unitTtile,
  IFNULL(TolEmpty.OP_quantity,0) OP_quantity,
IFNULL(TolEmpty.OP_UPrice,0) OP_UPrice ,
IFNULL(TolEmpty.OP_Amount,0) OP_Amount ,

IFNULL(TolEmpty.Pur_Qty,0) AS Pur_Qty,
IFNULL(TolEmpty.Pur_UPrice,0) AS Pur_UPrice,
IFNULL(TolEmpty.Pur_Amount,0) AS Pur_Amount,

IFNULL(TolEmpty.Sales_Qty,0) Sales_Qty,
IFNULL(TolEmpty.Sales_UPrice,0) Sales_UPrice,
IFNULL(TolEmpty.Sales_Amount,0) Sales_Amount,

IFNULL((IFNULL(TolEmpty.OP_quantity,0)+IFNULL(TolEmpty.Pur_Qty,0)-IFNULL(TolEmpty.Sales_Qty,0)),0) Closing_Qnty,

IFNULL((IFNULL(TolEmpty.OP_Amount,0)+ IFNULL(TolEmpty.Pur_Amount,0) )/ NULLIF(( IFNULL(TolEmpty.OP_quantity,0)+IFNULL(TolEmpty.Pur_Qty,0)),0)  ,0)  Closing_UPrice,

IFNULL((IFNULL((IFNULL(TolEmpty.OP_quantity,0)+IFNULL(TolEmpty.Pur_Qty,0)-IFNULL(TolEmpty.Sales_Qty,0)),0))*(IFNULL((IFNULL(TolEmpty.OP_Amount,0)+ IFNULL(TolEmpty.Pur_Amount,0) )/ NULLIF(( IFNULL(TolEmpty.OP_quantity,0)+IFNULL(TolEmpty.Pur_Qty,0)),0)  ,0) ),0) Closing_Amount,

IFNULL(Tol.OP_quantity,0) OP_quantity_refill,
IFNULL(Tol.Pur_quantity,0) Pur_quantity_refill,

IFNULL(Tol.S_quantity,0) S_quantity_refill,


IFNULL(IFNULL(Tol.OP_quantity,0)+IFNULL(Tol.Pur_quantity,0)-IFNULL(Tol.S_quantity,0),0) C_quantity_refill 
 


 



FROM
(

SELECT BaseT.product_id,BaseT.category_id,BaseT.unit_id,BaseT.brand_id, brand.brandName BrandName,BaseT.productName, productcategory.title CategoryName,unit.unitTtile,

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
 AND purchase_invoice_info.invoice_date < '".$startDate."'
  
 

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
  AND purchase_invoice_info.invoice_date < '".$startDate."'   
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
 AND sales_invoice_info.invoice_date < '".$startDate."'
  
 

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
AND purchase_invoice_info.invoice_date < '".$startDate."'
  

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
AND purchase_invoice_info.invoice_date < '".$startDate."'
  

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
AND sales_invoice_info.invoice_date < '".$startDate."'
     

GROUP BY   sales_return_details.product_id) Ret_Sales ON   BaseT.product_id= Ret_Sales.product_id  LEFT  OUTER JOIN  


/*END ReturnSales*/
 
 (
SELECT   
        sr.product_id,  SUM(sr.return_quantity)  Srt_quantity
      , SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0) AS Srt_UPrice       
     ,(SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0))*(SUM(sr.return_quantity) )  Srt_Amount
       
  FROM sales_return sr   
       
       WHERE  sr.is_active='Y' AND sr.is_delete='N'    
       AND sr.return_date < '".$startDate."'
         
       
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
 AND iai.date < '".$startDate."'
       
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


AND purchase_invoice_info.invoice_date  >= '".$startDate."'
 AND purchase_invoice_info.invoice_date  <= '".$endDate."'   
 
 
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


AND purchase_invoice_info.invoice_date  >= '".$startDate."'
 AND purchase_invoice_info.invoice_date  <= '".$endDate."'    
 
    
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

AND sales_invoice_info.invoice_date  >= '".$startDate."'
 AND sales_invoice_info.invoice_date  <= '".$endDate."'    
 
 
  
 

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


AND  purchase_invoice_info.invoice_date  >= '".$startDate."'
 AND  purchase_invoice_info.invoice_date <= '".$endDate."'  
 
 
  

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

AND  purchase_invoice_info.invoice_date  >= '".$startDate."'
 AND  purchase_invoice_info.invoice_date  <= '".$endDate."'  
 
 
  

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
AND  sales_invoice_info.invoice_date  >= '".$startDate."'
 AND  sales_invoice_info.invoice_date  <= '".$endDate."' 
 
     

GROUP BY   sales_return_details.product_id) Ret_Sales ON   BaseT.product_id= Ret_Sales.product_id  LEFT  OUTER JOIN  


/*END ReturnSales*/
 
 (
SELECT   
        sr.product_id,  SUM(sr.return_quantity)  Srt_quantity
      , SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0) AS Srt_UPrice       
     ,(SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0))*(SUM(sr.return_quantity) )  Srt_Amount
       
  FROM sales_return sr   
       
       WHERE  sr.is_active='Y' AND sr.is_delete='N'    
        
         AND sr.return_date  >= '".$startDate."'
 AND sr.return_date  <= '".$endDate."' 
       
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
 AND iai.date >= '".$startDate."'
AND iai.date <= '".$endDate."' 
       
       GROUP BY iad.product_id     
        ) Inv_Adj ON Inv_Adj.product_id=BaseT.product_id 
        
        
) TolEmpty

 LEFT JOIN package_products on TolEmpty.product_id=package_products.product_id
LEFT JOIN (
SELECT product.category_id,package_products.product_id,package_products.package_id 
FROM package_products LEFT JOIN product 
ON product.product_id=package_products.product_id 
WHERE product.category_id=2
) pp2 on pp2.package_id=package_products.package_id

LEFT JOIN (SELECT BaseT.product_id,BaseT.category_id,BaseT.unit_id,BaseT.brand_id, brand.brandName BrandName,BaseT.productName, title CategoryName,unit.unitTtile,

  IFNULL(OP_quantity,0) OP_quantity,
IFNULL(OP_UPrice,0) OP_UPrice 
,IFNULL(OP_Amount,0) OP_Amount,

IFNULL((IFNULL(Pur_quantity,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0)),0) Pur_quantity ,

IFNULL(((IFNULL((IFNULL(Pur_Amount,0)+ IFNULL(Srt_Amount,0) + IFNULL(INV_IN_Amount,0) ),0))/
NULLIF((IFNULL((IFNULL(Pur_quantity,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0) ),0)),0)),0)  Pur_UPrice,



IFNULL((IFNULL(Pur_Amount,0)+ IFNULL(Srt_Amount,0)+IFNULL(INV_IN_Amount,0)),0) Pur_Amount,



IFNULL((IFNULL(S_quantity,0) + IFNULL(INV_out_qty,0)),0) S_quantity ,


IFNULL(((IFNULL((IFNULL(S_Amount,0) + IFNULL(INV_OUT_Amount,0)),0))

/NULLIF((IFNULL((IFNULL(S_quantity,0) + IFNULL(INV_out_qty,0)),0)),0)),0) S_UPrice,
IFNULL((IFNULL(S_Amount,0) + IFNULL(INV_OUT_Amount,0)),0) S_Amount 
 

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
  ON product.product_id=T2.product_id  WHERE product.category_id<>1) BaseT LEFT OUTER JOIN
   
  
                      productcategory ON BaseT.category_id = productcategory.category_id LEFT OUTER JOIN
                      unit ON BaseT.unit_id = unit.unit_id LEFT OUTER JOIN
                      brand ON BaseT.brand_id = brand.brandId LEFT OUTER JOIN
                      
  (SELECT BaseT.product_id, BaseT.category_id , BaseT.unit_id, BaseT.brand_id,
 brandName BrandName,productName, title CategoryName,unitTtile,

(IFNULL((IFNULL(OP_quantity,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0)),0)) -
(IFNULL((IFNULL(S_quantity,0) + IFNULL(INV_out_qty,0)),0))  
OP_quantity  ,

IFNULL((IFNULL(Pur_Amount,0)+ IFNULL(Srt_Amount,0) + IFNULL(INV_IN_Amount,0)),0) / NULLIF((
IFNULL((IFNULL(Pur_quantity,0)+ IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0)),0)),0)   
OP_UPrice ,

  IFNULL(((IFNULL((IFNULL(OP_quantity,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0)),0)) -
(IFNULL((IFNULL(S_quantity,0) + IFNULL(INV_out_qty,0)),0)))*(IFNULL((IFNULL(Pur_Amount,0)+ 
IFNULL(Srt_Amount,0) + IFNULL(INV_IN_Amount,0)),0) / NULLIF((
IFNULL((IFNULL(Pur_quantity,0)+ IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0)),0)),0)) ,0)
 OP_Amount 
  
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
  ON product.product_id=T2.product_id  WHERE product.category_id<>1) BaseT LEFT OUTER JOIN
     productcategory ON BaseT.category_id = productcategory.category_id LEFT OUTER JOIN
     unit ON BaseT.unit_id = unit.unit_id LEFT OUTER JOIN
     brand ON BaseT.brand_id = brand.brandId LEFT OUTER JOIN
(
 
SELECT   
       pd.product_id  ,  SUM(pd.quantity)  OP_quantity
      , SUM(pd.quantity * pd.unit_price) / NULLIF(SUM(pd.quantity),0) AS OP_UPrice       
     ,(SUM(pd.quantity * pd.unit_price) / NULLIF(SUM(pd.quantity),0))*(SUM(pd.quantity) )  OP_Amount
       
  FROM purchase_details pd  LEFT OUTER JOIN
       
       purchase_invoice_info pii ON pd.purchase_invoice_id=pii.purchase_invoice_id
       
       WHERE  pd.is_active='Y' AND pd.is_delete='N' AND pii.invoice_date < '".$startDate."'   
       
       GROUP BY pd.product_id  
       
        
       ) opening  ON opening.product_id=BaseT.product_id  LEFT  OUTER JOIN
 
 (
SELECT   
       pd.product_id,   SUM(pd.quantity)  Pur_quantity
      , SUM(pd.quantity * pd.unit_price) / NULLIF(SUM(pd.quantity),0) AS Pur_UPrice       
     ,(SUM(pd.quantity * pd.unit_price) / NULLIF(SUM(pd.quantity),0))*(SUM(pd.quantity) )  Pur_Amount
       
  FROM purchase_details pd  LEFT OUTER JOIN      
       purchase_invoice_info pii ON pd.purchase_invoice_id=pii.purchase_invoice_id       
       WHERE  pd.is_active='Y' AND pd.is_delete='N'    
       AND pii.invoice_date < '".$startDate."' 
               
       
       GROUP BY pd.product_id      
      
       ) purchase ON  BaseT.product_id= purchase.product_id LEFT  OUTER JOIN
       
    (
SELECT   
        sr.product_id,  SUM(sr.return_quantity)  Srt_quantity
      , SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0) AS Srt_UPrice       
     ,(SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0))*(SUM(sr.return_quantity) )  Srt_Amount
       
  FROM sales_return sr   
       
       WHERE  sr.is_active='Y' AND sr.is_delete='N'    
       AND sr.return_date < '".$startDate."' 
         
       
       GROUP BY sr.product_id
       
      
       ) sales_Ret ON  BaseT.product_id= sales_Ret.product_id LEFT  OUTER JOIN   
       (
SELECT   
       sd.product_id,   SUM(sd.quantity)  S_quantity
      , SUM(sd.quantity * sd.unit_price) / NULLIF(SUM(sd.quantity),0)
       AS S_UPrice       
     ,(SUM(sd.quantity * sd.unit_price) / NULLIF(SUM(sd.quantity),0))*(SUM(sd.quantity) )  S_Amount
       
  FROM sales_details sd  LEFT OUTER JOIN
       
       sales_invoice_info sii ON sd.sales_invoice_id=sii.sales_invoice_id
       
       WHERE   sd.is_active='Y' AND sd.is_delete='N'  
 AND sii.invoice_date < '".$startDate."'  
       
       GROUP BY sd.product_id 
       
       
        ) Sales ON Sales.product_id=BaseT.product_id 
        LEFT  OUTER JOIN   
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
 AND iai.date < '".$startDate."'
       
       GROUP BY iad.product_id     
        ) Inv_Adj ON Inv_Adj.product_id=BaseT.product_id 
        
        ) Opening_All     
 ON Opening_All.product_id=BaseT.product_id  LEFT  OUTER JOIN
 
 (
SELECT   
       pd.product_id,   SUM(pd.quantity)  Pur_quantity
      , SUM(pd.quantity * pd.unit_price) / NULLIF(SUM(pd.quantity),0) AS Pur_UPrice       
     ,(SUM(pd.quantity * pd.unit_price) / NULLIF(SUM(pd.quantity),0))*(SUM(pd.quantity) )  Pur_Amount
       
  FROM purchase_details pd  LEFT OUTER JOIN      
       purchase_invoice_info pii ON pd.purchase_invoice_id=pii.purchase_invoice_id       
       WHERE  pd.is_active='Y' AND pd.is_delete='N'    
       AND pii.invoice_date >= '".$startDate."' 
       AND pii.invoice_date  <= '".$endDate."'          
       
       GROUP BY pd.product_id      
      
       ) purchase ON  BaseT.product_id= purchase.product_id LEFT  OUTER JOIN
       
    (
SELECT   
        sr.product_id,  SUM(sr.return_quantity)  Srt_quantity
      , SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0) AS Srt_UPrice       
     ,(SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0))*(SUM(sr.return_quantity) )  Srt_Amount
       
  FROM sales_return sr   
       
       WHERE  sr.is_active='Y' AND sr.is_delete='N'    
       AND sr.return_date >= '".$endDate."'
       AND sr.return_date  <= '".$endDate."' 
         
       
       GROUP BY sr.product_id
       
      
       ) sales_Ret ON  BaseT.product_id= sales_Ret.product_id LEFT  OUTER JOIN   
       (
SELECT   
       sd.product_id,   SUM(sd.quantity)  S_quantity
      , SUM(sd.quantity * sd.unit_price) / NULLIF(SUM(sd.quantity),0) AS S_UPrice       
     ,(SUM(sd.quantity * sd.unit_price) / NULLIF(SUM(sd.quantity),0))*(SUM(sd.quantity) )  S_Amount
       
  FROM sales_details sd  LEFT OUTER JOIN
       
       sales_invoice_info sii ON sd.sales_invoice_id=sii.sales_invoice_id
       
       WHERE   sd.is_active='Y' AND sd.is_delete='N'  
 AND sii.invoice_date >= '".$endDate."'
AND sii.invoice_date  <= '".$endDate."' 
       
       GROUP BY sd.product_id 
       
       
        ) Sales ON Sales.product_id=BaseT.product_id 
        LEFT  OUTER JOIN   
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
 AND iai.date >= '".$endDate."'
AND iai.date  <= '".$endDate."' 
       
       GROUP BY iad.product_id     
        ) Inv_Adj ON Inv_Adj.product_id=BaseT.product_id 
        
        
        
            ) Tol on Tol.product_id=pp2.product_id


WHERE 1=1";

        if($productCatagory !='All'){
            $query5.=" AND TolEmpty.category_id=".$productCatagory;
        }
        if($productBrand !='All'){
            $query5.=" AND TolEmpty.brand_id=".$productBrand;
        }if($productId !='All'){
            $query5.=" AND TolEmpty.product_id=".$productId;
        }
        log_message('error','query2 :'.print_r($query5,true));
        $query = $this->db->query($query5);
        $result = $query->result();
        foreach ($result as $key => $value) {
            $array[$value->CategoryName ][$value->BrandName][] = $value;
        }
        return $array;
    }


    public function stock_report_with_branch($given_branch_id,$productCatagory,$productBrand,$productId,$startDate,$endDate,$subcategory,$color,$size,$model_id='all'){
        $branch_id=" 1=1";
        $inventory_adjustment_details_branch_id=" 1=1";
        if($given_branch_id !='all'){
            $branch_id=" branch_id=".$given_branch_id;
            $inventory_adjustment_details_branch_id=" inventory_adjustment_details.BranchAutoId=".$given_branch_id;
        }



$query4="SELECT 
IFNULL(tb_subcategory.SubCatName,'NA') SubCatName,
IFNULL(tb_model.Model,'NA') Model_name,
IFNULL(tb_size.Size,'NA') SizeName,
IFNULL(tb_color.Color,'NA') ColorName,
Tol.branch_id,Tol.branch_name,
 
  Tol.product_id, Tol.category_id, Tol.unit_id, Tol.brand_id,
    Tol.BrandName, Tol.productName,   Tol.CategoryName, Tol.unitTtile,

IFNULL(Tol.OP_quantity,0) OP_quantity,
IFNULL(Tol.OP_UPrice,0) OP_UPrice ,
IFNULL(Tol.OP_Amount,0) OP_Amount,
IFNULL(Tol.Pur_quantity,0) Pur_quantity,
IFNULL(Tol.Pur_UPrice,0) Pur_UPrice,
IFNULL(Tol.Pur_Amount,0) Pur_Amount,
IFNULL(Tol.S_quantity,0) S_quantity,
IFNULL(Tol.S_UPrice,0) S_UPrice,
IFNULL(Tol.S_Amount,0) S_Amount,

IFNULL(IFNULL(Tol.OP_quantity,0)+IFNULL(Tol.Pur_quantity,0)-IFNULL(Tol.S_quantity,0),0) C_quantity ,

IFNULL(IFNULL((IFNULL(Tol.OP_Amount,0)+IFNULL(Tol.Pur_Amount,0)) ,0)/NULLIF(((IFNULL(Tol.OP_quantity,0)+IFNULL(Tol.Pur_quantity,0))),0),0) C_UPrice,
 

 IFNULL(((IFNULL(IFNULL(Tol.OP_quantity,0)+IFNULL(Tol.Pur_quantity,0)-IFNULL(Tol.S_quantity,0),0))*
 (IFNULL(IFNULL((IFNULL(Tol.OP_Amount,0)+IFNULL(Tol.Pur_Amount,0)) ,0)/NULLIF(((IFNULL(Tol.OP_quantity,0)+IFNULL(Tol.Pur_quantity,0))),0),0))),0) C_Amount

FROM


(

SELECT BaseT.product_id,BaseT.category_id,BaseT.unit_id,BaseT.brand_id, 
brand.brandName BrandName,BaseT.productName, title CategoryName,unit.unitTtile,
BaseT.branch_id,branch.branch_name,

  IFNULL(OP_quantity,0) OP_quantity,
IFNULL(OP_UPrice,0) OP_UPrice 
,IFNULL(OP_Amount,0) OP_Amount,

IFNULL((IFNULL(Pur_quantity,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0)),0) Pur_quantity ,

IFNULL(((IFNULL((IFNULL(Pur_Amount,0)+ IFNULL(Srt_Amount,0) + IFNULL(INV_IN_Amount,0) ),0))/
NULLIF((IFNULL((IFNULL(Pur_quantity,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0) ),0)),0)),0)  Pur_UPrice,



IFNULL((IFNULL(Pur_Amount,0)+ IFNULL(Srt_Amount,0)+IFNULL(INV_IN_Amount,0)),0) Pur_Amount,



IFNULL((IFNULL(S_quantity,0) + IFNULL(INV_out_qty,0)),0) S_quantity ,


IFNULL(((IFNULL((IFNULL(S_Amount,0) + IFNULL(INV_OUT_Amount,0)),0))

/NULLIF((IFNULL((IFNULL(S_quantity,0) + IFNULL(INV_out_qty,0)),0)),0)),0) S_UPrice,
IFNULL((IFNULL(S_Amount,0) + IFNULL(INV_OUT_Amount,0)),0) S_Amount 
 

FROM 
 (
SELECT product.product_id,T2.branch_id, product.productName,product.category_id ,product.unit_id,product.brand_id
  FROM  
 (SELECT product_id,branch_id  FROM   purchase_details Where 
 ".$branch_id."
  union  
  SELECT product_id,branch_id FROM   purchase_return_details  Where 
 ".$branch_id."
 
  union
SELECT inventory_adjustment_details.product_id as product_id,inventory_adjustment_details.BranchAutoId AS branch_id FROM inventory_adjustment_details WHERE 
".$inventory_adjustment_details_branch_id."
-- inventory_adjustment_details.BranchAutoId
  )   
    T2 LEFT OUTER JOIN 
   product  
  ON product.product_id=T2.product_id  WHERE product.category_id<>1) BaseT LEFT OUTER JOIN
                      productcategory ON BaseT.category_id = productcategory.category_id LEFT OUTER JOIN
                      unit ON BaseT.unit_id = unit.unit_id LEFT OUTER JOIN
                      brand ON BaseT.brand_id = brand.brandId LEFT OUTER JOIN 
     branch ON BaseT.branch_id=branch.branch_id LEFT OUTER JOIN 
                      
  (
SELECT BaseT.product_id, BaseT.category_id , BaseT.unit_id, BaseT.brand_id,
 brandName BrandName,productName, title CategoryName,unitTtile,
BaseT.branch_id,branch.branch_name,

(IFNULL((IFNULL(OP_quantity,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0)),0)) -
(IFNULL((IFNULL(S_quantity,0) + IFNULL(INV_out_qty,0)),0))  
OP_quantity  ,

IFNULL((IFNULL(OP_Amount,0)+ IFNULL(Srt_Amount,0) + IFNULL(INV_IN_Amount,0)),0) / NULLIF((
IFNULL((IFNULL(OP_quantity,0)+ IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0)),0)),0)   
OP_UPrice ,

  IFNULL(((IFNULL((IFNULL(OP_quantity,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0)),0)) -
(IFNULL((IFNULL(S_quantity,0) + IFNULL(INV_out_qty,0)),0)))*(IFNULL((IFNULL(OP_Amount,0)+ 
IFNULL(Srt_Amount,0) + IFNULL(INV_IN_Amount,0)),0) / NULLIF((
IFNULL((IFNULL(OP_quantity,0)+ IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0)),0)),0)) ,0)
 OP_Amount 
  
FROM 

(
SELECT product.product_id,T2.branch_id, product.productName,product.category_id ,product.unit_id,product.brand_id
  FROM  
 (SELECT product_id,branch_id  FROM   purchase_details Where 
 ".$branch_id."
  union  
  SELECT product_id,branch_id FROM   purchase_return_details  Where 
 ".$branch_id."
 
 union
SELECT inventory_adjustment_details.product_id as product_id,inventory_adjustment_details.BranchAutoId AS branch_id FROM inventory_adjustment_details WHERE 
".$inventory_adjustment_details_branch_id."
-- inventory_adjustment_details.BranchAutoId
  )   
    T2 LEFT OUTER JOIN 
   product  
  ON product.product_id=T2.product_id  WHERE product.category_id<>1) BaseT LEFT OUTER JOIN
                      productcategory ON BaseT.category_id = productcategory.category_id LEFT OUTER JOIN
                      unit ON BaseT.unit_id = unit.unit_id LEFT OUTER JOIN
                      brand ON BaseT.brand_id = brand.brandId LEFT OUTER JOIN 
     branch ON BaseT.branch_id=branch.branch_id LEFT OUTER JOIN 

(
 
SELECT   
       pd.product_id  , pd.branch_id, 
       
        SUM(pd.quantity)  OP_quantity
      , SUM(pd.quantity * pd.unit_price) / NULLIF(SUM(pd.quantity),0) AS OP_UPrice       
     ,(SUM(pd.quantity * pd.unit_price) / NULLIF(SUM(pd.quantity),0))*(SUM(pd.quantity) )  OP_Amount
       
  FROM purchase_details pd  LEFT OUTER JOIN
       
       purchase_invoice_info pii ON pd.purchase_invoice_id=pii.purchase_invoice_id AND pd.branch_id=pii.branch_id
       
       WHERE  pd.is_active='Y' AND pd.is_delete='N' AND pii.invoice_date <  '".$startDate."'  
       
       GROUP BY pd.product_id  ,pd.branch_id
       
        
       ) opening  ON opening.product_id=BaseT.product_id AND opening.branch_id=BaseT.branch_id  LEFT  OUTER JOIN
 

       
    (
        SELECT
        sr.product_id, 
        sr.branch_id, 
        SUM(sr.quantity)  Srt_quantity,
        SUM(sr.quantity * sr.price) / NULLIF(SUM(sr.quantity),0) AS Srt_UPrice ,
        (SUM(sr.quantity * sr.price) / NULLIF(SUM(sr.quantity),0))*(SUM(sr.quantity) )  Srt_Amount
        FROM
            stock sr
        WHERE
            sr.form_id = 5
        AND sr.type=1 
       AND sr.invoice_date < '".$startDate."'  
         
       
       GROUP BY sr.product_id ,sr.branch_id
       
      
       ) sales_Ret ON  BaseT.product_id= sales_Ret.product_id AND BaseT.branch_id= sales_Ret.branch_id LEFT  OUTER JOIN   
       (
SELECT   
       sd.product_id,sd.branch_id,   SUM(sd.quantity)  S_quantity
      , SUM(sd.quantity * sd.unit_price) / NULLIF(SUM(sd.quantity),0)
       AS S_UPrice       
     ,(SUM(sd.quantity * sd.unit_price) / NULLIF(SUM(sd.quantity),0))*(SUM(sd.quantity) )  S_Amount
       
  FROM sales_details sd  LEFT OUTER JOIN
       
       sales_invoice_info sii ON sd.sales_invoice_id=sii.sales_invoice_id AND sd.branch_id=sii.branch_id 
       
       WHERE   sd.is_active='Y' AND sd.is_delete='N'  
 AND sii.invoice_date < '".$startDate."'
       
       GROUP BY sd.product_id ,sd.branch_id
       
       
        ) Sales ON Sales.product_id=BaseT.product_id AND Sales.branch_id=BaseT.branch_id 
        LEFT  OUTER JOIN   
       (
SELECT   
       iad.product_id,   iad.BranchAutoId,
       SUM(iad.in_qty)  INV_in_qty,  
       SUM(iad.out_qty)  INV_out_qty, 
       
        SUM(iad.in_qty * iad.unit_price) / NULLIF(SUM(iad.in_qty),0) AS INV_IN_UPrice  ,     
        SUM(iad.out_qty * iad.unit_price) / NULLIF(SUM(iad.out_qty),0) AS INV_OUT_UPrice ,  
      
          
      (SUM(iad.in_qty * iad.unit_price) / NULLIF(SUM(iad.in_qty),0))*(SUM(iad.in_qty))  INV_IN_Amount,
      (SUM(iad.out_qty * iad.unit_price) / NULLIF(SUM(iad.out_qty),0))*( SUM(iad.out_qty))  INV_OUT_Amount 
       
  FROM inventory_adjustment_details iad  LEFT OUTER JOIN
       
       inventory_adjustment_info iai ON iad.inv_adjustment_info_id=iai.id AND iad.BranchAutoId=iai.BranchAutoId
       
       WHERE   iad.is_active='Y' AND iad.is_delete='N'  
 AND iai.date < '".$startDate."'
       
       GROUP BY iad.product_id  ,iad.branch_id   
        ) Inv_Adj ON Inv_Adj.product_id=BaseT.product_id  AND Inv_Adj.BranchAutoId=BaseT.branch_id
--         GROUP BY BaseT.product_id,BaseT.BranchAutoId
        

) Opening_All   
          
 ON Opening_All.product_id=BaseT.product_id  and Opening_All.branch_id=BaseT.branch_id   LEFT  OUTER JOIN
 
 (
SELECT   
       pd.product_id,pd.branch_id,   SUM(pd.quantity)  Pur_quantity
      , SUM(pd.quantity * pd.unit_price) / NULLIF(SUM(pd.quantity),0) AS Pur_UPrice       
     ,(SUM(pd.quantity * pd.unit_price) / NULLIF(SUM(pd.quantity),0))*(SUM(pd.quantity) )  Pur_Amount
       
  FROM purchase_details pd  LEFT OUTER JOIN      
       purchase_invoice_info pii ON pd.purchase_invoice_id=pii.purchase_invoice_id  AND pd.branch_id=pii.branch_id       
       WHERE  pd.is_active='Y' AND pd.is_delete='N'    
       AND pii.invoice_date >=  '".$startDate."' 
       AND pii.invoice_date <= '".$endDate."'         
       
       GROUP BY pd.product_id      ,pd.branch_id
      
       ) purchase ON  BaseT.product_id= purchase.product_id AND BaseT.branch_id= purchase.branch_id   LEFT  OUTER JOIN
       
    (
        SELECT
        sr.product_id, 
        sr.branch_id, 
        SUM(sr.quantity)  Srt_quantity,
        SUM(sr.quantity * sr.price) / NULLIF(SUM(sr.quantity),0) AS Srt_UPrice ,
        (SUM(sr.quantity * sr.price) / NULLIF(SUM(sr.quantity),0))*(SUM(sr.quantity) )  Srt_Amount
        FROM
            stock sr
        WHERE
            sr.form_id = 5
        AND sr.type=1   
       AND sr.invoice_date >= '".$startDate."'
       AND sr.invoice_date <= '".$endDate."'  
         
       
       GROUP BY sr.product_id,sr.branch_id 
       
      
       ) sales_Ret ON  BaseT.product_id= sales_Ret.product_id AND  BaseT.branch_id= sales_Ret.branch_id LEFT  OUTER JOIN   
       (
SELECT   
       sd.product_id,sd.branch_id,   SUM(sd.quantity)  S_quantity
      , SUM(sd.quantity * sd.unit_price) / NULLIF(SUM(sd.quantity),0) AS S_UPrice       
     ,(SUM(sd.quantity * sd.unit_price) / NULLIF(SUM(sd.quantity),0))*(SUM(sd.quantity) )  S_Amount
       
  FROM sales_details sd  LEFT OUTER JOIN
       
       sales_invoice_info sii ON sd.sales_invoice_id=sii.sales_invoice_id AND sd.branch_id=sii.branch_id
       
       WHERE   sd.is_active='Y' AND sd.is_delete='N'  
 AND sii.invoice_date >= '".$startDate."'
AND sii.invoice_date <= '".$endDate."' 
       
       GROUP BY sd.product_id ,sd.branch_id
       
       
        ) Sales ON Sales.product_id=BaseT.product_id AND Sales.branch_id=BaseT.branch_id 
        LEFT  OUTER JOIN   
       (
SELECT   
       iad.product_id,   iad.BranchAutoId, 
       SUM(iad.in_qty)  INV_in_qty,  
       SUM(iad.out_qty)  INV_out_qty, 
       
        SUM(iad.in_qty * iad.unit_price) / NULLIF(SUM(iad.in_qty),0) AS INV_IN_UPrice  ,     
        SUM(iad.out_qty * iad.unit_price) / NULLIF(SUM(iad.out_qty),0) AS INV_OUT_UPrice ,  
      
          
      (SUM(iad.in_qty * iad.unit_price) / NULLIF(SUM(iad.in_qty),0))*(SUM(iad.in_qty))  INV_IN_Amount,
      (SUM(iad.out_qty * iad.unit_price) / NULLIF(SUM(iad.out_qty),0))*( SUM(iad.out_qty))  INV_OUT_Amount 
       
  FROM inventory_adjustment_details iad  LEFT OUTER JOIN       
       inventory_adjustment_info iai ON iad.inv_adjustment_info_id=iai.id AND   iad.BranchAutoId=iai.BranchAutoId 
       
       WHERE   iad.is_active='Y' AND iad.is_delete='N'  
                AND iai.date >= '".$startDate."'
                AND iai.date <= '".$endDate."'    
       
       GROUP BY iad.product_id, iad.BranchAutoId     
        ) Inv_Adj ON Inv_Adj.product_id=BaseT.product_id AND   Inv_Adj.BranchAutoId=BaseT.branch_id 
        
        
        
            ) Tol
LEFT JOIN product ON product.product_id=Tol.product_id
LEFT JOIN tb_subcategory ON tb_subcategory.SubCatID=product.subcategoryID
LEFT JOIN tb_color ON tb_color.ColorID=product.colorID
LEFT JOIN tb_size ON tb_size.SizeID=product.sizeID
LEFT JOIN tb_model ON tb_model.ModelID=product.modelID

 WHERE 1=1 ";


        if($productCatagory !='all'){
            $query4.=" AND Tol.category_id=".$productCatagory;
        }
        if($productBrand !='all'){
            $query4.=" AND Tol.brand_id=".$productBrand;
        }
        if($productId !='all'){
            $query4.=" AND Tol.product_id=".$productId;
        }
        if($subcategory !='all'){
            $query4.=" AND tb_subcategory.SubCatID=".$subcategory;
        }
        if($size !='all'){
            $query4.=" AND tb_size.SizeID=".$size;
        }
        if($color !='all'){
            $query4.=" AND tb_color.ColorID=".$color;
        }
        if($model_id !='all'){
            $query4.=" AND tb_model.ModelID=".$model_id;
        }


        $query4.=" ORDER BY  Tol.branch_name,Tol.product_id,tb_subcategory.SubCatID,tb_color.ColorID,tb_size.SizeID";
        log_message('error','this is stock report'.print_r($query4,true));
        $query = $this->db->query($query4);
        $result = $query->result();
        if ($this->business_type == "MOTORCYCLE") {
            foreach ($result as $key => $value) {
                $array[$value->branch_name][$value->CategoryName][$value->BrandName][$value->Model_name][] = $value;
            }
        }else{
            foreach ($result as $key => $value) {
                $array[$value->branch_name][$value->CategoryName][$value->BrandName][] = $value;
            }
        }
        return $array;

    }
    /*public function stock_report_with_branch_bk($given_branch_id,$productCatagory,$productBrand,$productId,$startDate,$endDate,$subcategory,$color,$size,$model_id='all'){
        $branch_id=" 1=1";
        $inventory_adjustment_details_branch_id=" 1=1";
        if($given_branch_id !='all'){
            $branch_id=" branch_id=".$given_branch_id;
            $inventory_adjustment_details_branch_id=" inventory_adjustment_details.BranchAutoId=".$given_branch_id;
        }

        $query5="SELECT 
IFNULL(tb_subcategory.SubCatName,'NA') SubCatName,
IFNULL(tb_size.Size,'NA') SizeName,
IFNULL(tb_color.Color,'NA') ColorName,
Tol.branch_id,Tol.branch_name,
branch_id,branch_name,
 
  product_id, category_id, unit_id, brand_id,
    brandName BrandName, productName,   CategoryName, unitTtile,

IFNULL(OP_quantity,0) OP_quantity,
IFNULL(OP_UPrice,0) OP_UPrice ,
IFNULL(OP_Amount,0) OP_Amount,
IFNULL(Pur_quantity,0) Pur_quantity,
IFNULL(Pur_UPrice,0) Pur_UPrice,
IFNULL(Pur_Amount,0) Pur_Amount,
IFNULL(S_quantity,0) S_quantity,
IFNULL(S_UPrice,0) S_UPrice,
IFNULL(S_Amount,0) S_Amount,

IFNULL(IFNULL(OP_quantity,0)+IFNULL(Pur_quantity,0)-IFNULL(S_quantity,0),0) C_quantity ,

IFNULL(IFNULL((IFNULL(OP_Amount,0)+IFNULL(Pur_Amount,0)) ,0)/NULLIF(((IFNULL(OP_quantity,0)+IFNULL(Pur_quantity,0))),0),0) C_UPrice,
 

 IFNULL(((IFNULL(IFNULL(OP_quantity,0)+IFNULL(Pur_quantity,0)-IFNULL(S_quantity,0),0))*
 (IFNULL(IFNULL((IFNULL(OP_Amount,0)+IFNULL(Pur_Amount,0)) ,0)/NULLIF(((IFNULL(OP_quantity,0)+IFNULL(Pur_quantity,0))),0),0))),0) C_Amount


FROM


(

SELECT BaseT.product_id,BaseT.category_id,BaseT.unit_id,BaseT.brand_id, 
brand.brandName BrandName,BaseT.productName, title CategoryName,unit.unitTtile,
BaseT.branch_id,branch.branch_name,

  IFNULL(OP_quantity,0) OP_quantity,
IFNULL(OP_UPrice,0) OP_UPrice 
,IFNULL(OP_Amount,0) OP_Amount,

IFNULL((IFNULL(Pur_quantity,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0)),0) Pur_quantity ,

IFNULL(((IFNULL((IFNULL(Pur_Amount,0)+ IFNULL(Srt_Amount,0) + IFNULL(INV_IN_Amount,0) ),0))/
NULLIF((IFNULL((IFNULL(Pur_quantity,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0) ),0)),0)),0)  Pur_UPrice,



IFNULL((IFNULL(Pur_Amount,0)+ IFNULL(Srt_Amount,0)+IFNULL(INV_IN_Amount,0)),0) Pur_Amount,



IFNULL((IFNULL(S_quantity,0) + IFNULL(INV_out_qty,0)),0) S_quantity ,


IFNULL(((IFNULL((IFNULL(S_Amount,0) + IFNULL(INV_OUT_Amount,0)),0))

/NULLIF((IFNULL((IFNULL(S_quantity,0) + IFNULL(INV_out_qty,0)),0)),0)),0) S_UPrice,
IFNULL((IFNULL(S_Amount,0) + IFNULL(INV_OUT_Amount,0)),0) S_Amount 
 

FROM 
 (
SELECT product.product_id,T2.branch_id, product.productName,product.category_id ,product.unit_id,product.brand_id
  FROM  
 (SELECT product_id,branch_id  FROM   purchase_details Where 
".$branch_id."
  union  
  SELECT product_id,branch_id FROM   purchase_return_details  Where 
".$branch_id."
  )   
    T2 LEFT OUTER JOIN 
   product  
  ON product.product_id=T2.product_id  WHERE product.category_id<>1) BaseT LEFT OUTER JOIN
                      productcategory ON BaseT.category_id = productcategory.category_id LEFT OUTER JOIN
                      unit ON BaseT.unit_id = unit.unit_id LEFT OUTER JOIN
                      brand ON BaseT.brand_id = brand.brandId LEFT OUTER JOIN 
     branch ON BaseT.branch_id=branch.branch_id LEFT OUTER JOIN 
                      
  (SELECT BaseT.product_id, BaseT.category_id , BaseT.unit_id, BaseT.brand_id,
 brandName BrandName,productName, title CategoryName,unitTtile,
BaseT.branch_id,branch.branch_name,

(IFNULL((IFNULL(OP_quantity,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0)),0)) -
(IFNULL((IFNULL(S_quantity,0) + IFNULL(INV_out_qty,0)),0))  
OP_quantity  ,

IFNULL((IFNULL(OP_Amount,0)+ IFNULL(Srt_Amount,0) + IFNULL(INV_IN_Amount,0)),0) / NULLIF((
IFNULL((IFNULL(OP_quantity,0)+ IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0)),0)),0)   
OP_UPrice ,

  IFNULL(((IFNULL((IFNULL(OP_quantity,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0)),0)) -
(IFNULL((IFNULL(S_quantity,0) + IFNULL(INV_out_qty,0)),0)))*(IFNULL((IFNULL(OP_Amount,0)+ 
IFNULL(Srt_Amount,0) + IFNULL(INV_IN_Amount,0)),0) / NULLIF((
IFNULL((IFNULL(OP_quantity,0)+ IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0)),0)),0)) ,0)
 OP_Amount 
FROM 

(
SELECT product.product_id,T2.branch_id, product.productName,product.category_id ,product.unit_id,product.brand_id
  FROM  
 (SELECT product_id,branch_id  FROM   purchase_details Where 
".$branch_id."
  union  
  SELECT product_id,branch_id FROM   purchase_return_details  Where 
".$branch_id."
  )   
    T2 LEFT OUTER JOIN 
   product  
  ON product.product_id=T2.product_id  WHERE product.category_id<>1) BaseT LEFT OUTER JOIN
                      productcategory ON BaseT.category_id = productcategory.category_id LEFT OUTER JOIN
                      unit ON BaseT.unit_id = unit.unit_id LEFT OUTER JOIN
                      brand ON BaseT.brand_id = brand.brandId LEFT OUTER JOIN 
     branch ON BaseT.branch_id=branch.branch_id LEFT OUTER JOIN 

(
 
SELECT   
       pd.product_id  , pd.branch_id, 
       
        SUM(pd.quantity)  OP_quantity
      , SUM(pd.quantity * pd.unit_price) / NULLIF(SUM(pd.quantity),0) AS OP_UPrice       
     ,(SUM(pd.quantity * pd.unit_price) / NULLIF(SUM(pd.quantity),0))*(SUM(pd.quantity) )  OP_Amount
       
  FROM purchase_details pd  LEFT OUTER JOIN
       
       purchase_invoice_info pii ON pd.purchase_invoice_id=pii.purchase_invoice_id AND pd.branch_id=pii.branch_id
       
       WHERE  pd.is_active='Y' AND pd.is_delete='N' AND pii.invoice_date < '".$endDate."'  
       
       GROUP BY pd.product_id  ,pd.branch_id
       
        
       ) opening  ON opening.product_id=BaseT.product_id AND opening.branch_id=BaseT.branch_id  LEFT  OUTER JOIN
 

       
    (
SELECT   
        sr.product_id, sr.branch_id, SUM(sr.return_quantity)  Srt_quantity
      , SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0) AS Srt_UPrice       
     ,(SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0))*(SUM(sr.return_quantity) )  Srt_Amount
       
  FROM sales_return sr   
       
       WHERE  sr.is_active='Y' AND sr.is_delete='N'    
       AND sr.return_date < '".$startDate."'  
         
       
       GROUP BY sr.product_id ,sr.branch_id
       
      
       ) sales_Ret ON  BaseT.product_id= sales_Ret.product_id AND BaseT.branch_id= sales_Ret.branch_id LEFT  OUTER JOIN   
       (
SELECT   
       sd.product_id,sd.branch_id,   SUM(sd.quantity)  S_quantity
      , SUM(sd.quantity * sd.unit_price) / NULLIF(SUM(sd.quantity),0)
       AS S_UPrice       
     ,(SUM(sd.quantity * sd.unit_price) / NULLIF(SUM(sd.quantity),0))*(SUM(sd.quantity) )  S_Amount
       
  FROM sales_details sd  LEFT OUTER JOIN
       
       sales_invoice_info sii ON sd.sales_invoice_id=sii.sales_invoice_id AND sd.branch_id=sii.branch_id 
       
       WHERE   sd.is_active='Y' AND sd.is_delete='N'  
 AND sii.invoice_date < '".$startDate."' 
       
       GROUP BY sd.product_id ,sd.branch_id
       
       
        ) Sales ON Sales.product_id=BaseT.product_id AND Sales.branch_id=BaseT.branch_id 
        LEFT  OUTER JOIN   
       (
SELECT   
       iad.product_id,   iad.branch_id,
       SUM(iad.in_qty)  INV_in_qty,  
       SUM(iad.out_qty)  INV_out_qty, 
       
        SUM(iad.in_qty * iad.unit_price) / NULLIF(SUM(iad.in_qty),0) AS INV_IN_UPrice  ,     
        SUM(iad.out_qty * iad.unit_price) / NULLIF(SUM(iad.out_qty),0) AS INV_OUT_UPrice ,  
      
          
      (SUM(iad.in_qty * iad.unit_price) / NULLIF(SUM(iad.in_qty),0))*(SUM(iad.in_qty))  INV_IN_Amount,
      (SUM(iad.out_qty * iad.unit_price) / NULLIF(SUM(iad.out_qty),0))*( SUM(iad.out_qty))  INV_OUT_Amount 
       
  FROM inventory_adjustment_details iad  LEFT OUTER JOIN
       
       inventory_adjustment_info iai ON iad.inv_adjustment_info_id=iai.id AND iad.branch_id=iai.BranchAutoId
       
       WHERE   iad.is_active='Y' AND iad.is_delete='N'  
 AND iai.date < '".$startDate."'
       
       GROUP BY iad.product_id  ,iad.branch_id   
        ) Inv_Adj ON Inv_Adj.product_id=BaseT.product_id  AND Inv_Adj.branch_id=BaseT.branch_id
        
        ) Opening_All   
          
 ON Opening_All.product_id=BaseT.product_id  and Opening_All.branch_id=BaseT.branch_id   LEFT  OUTER JOIN
 
 (
SELECT   
       pd.product_id,pd.branch_id,   SUM(pd.quantity)  Pur_quantity
      , SUM(pd.quantity * pd.unit_price) / NULLIF(SUM(pd.quantity),0) AS Pur_UPrice       
     ,(SUM(pd.quantity * pd.unit_price) / NULLIF(SUM(pd.quantity),0))*(SUM(pd.quantity) )  Pur_Amount
       
  FROM purchase_details pd  LEFT OUTER JOIN      
       purchase_invoice_info pii ON pd.purchase_invoice_id=pii.purchase_invoice_id  AND pd.branch_id=pii.branch_id       
       WHERE  pd.is_active='Y' AND pd.is_delete='N'    
       AND pii.invoice_date >= '".$startDate."' 
       AND pii.invoice_date <= '".$endDate."'         
       
       GROUP BY pd.product_id      ,pd.branch_id
      
       ) purchase ON  BaseT.product_id= purchase.product_id AND BaseT.branch_id= purchase.branch_id   LEFT  OUTER JOIN
       
    (
SELECT   
        sr.product_id,sr.branch_id , SUM(sr.return_quantity)  Srt_quantity
      , SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0) AS Srt_UPrice       
     ,(SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0))*(SUM(sr.return_quantity) )  Srt_Amount
       
  FROM sales_return sr   
       
       WHERE  sr.is_active='Y' AND sr.is_delete='N'    
       AND sr.return_date >= '".$startDate."'
       AND sr.return_date <= '".$endDate."'  
         
       
       GROUP BY sr.product_id,sr.branch_id 
       
      
       ) sales_Ret ON  BaseT.product_id= sales_Ret.product_id AND  BaseT.branch_id= sales_Ret.branch_id LEFT  OUTER JOIN   
       (
SELECT   
       sd.product_id,sd.branch_id,   SUM(sd.quantity)  S_quantity
      , SUM(sd.quantity * sd.unit_price) / NULLIF(SUM(sd.quantity),0) AS S_UPrice       
     ,(SUM(sd.quantity * sd.unit_price) / NULLIF(SUM(sd.quantity),0))*(SUM(sd.quantity) )  S_Amount
       
  FROM sales_details sd  LEFT OUTER JOIN
       
       sales_invoice_info sii ON sd.sales_invoice_id=sii.sales_invoice_id AND sd.branch_id=sii.branch_id
       
       WHERE   sd.is_active='Y' AND sd.is_delete='N'  
 AND sii.invoice_date >= '".$startDate."'
AND sii.invoice_date <= '".$endDate."'  
       
       GROUP BY sd.product_id ,sd.branch_id
       
       
        ) Sales ON Sales.product_id=BaseT.product_id AND Sales.branch_id=BaseT.branch_id 
        LEFT  OUTER JOIN   
       (
SELECT   
       iad.product_id,   iad.branch_id, 
       SUM(iad.in_qty)  INV_in_qty,  
       SUM(iad.out_qty)  INV_out_qty, 
       
        SUM(iad.in_qty * iad.unit_price) / NULLIF(SUM(iad.in_qty),0) AS INV_IN_UPrice  ,     
        SUM(iad.out_qty * iad.unit_price) / NULLIF(SUM(iad.out_qty),0) AS INV_OUT_UPrice ,  
      
          
      (SUM(iad.in_qty * iad.unit_price) / NULLIF(SUM(iad.in_qty),0))*(SUM(iad.in_qty))  INV_IN_Amount,
      (SUM(iad.out_qty * iad.unit_price) / NULLIF(SUM(iad.out_qty),0))*( SUM(iad.out_qty))  INV_OUT_Amount 
       
  FROM inventory_adjustment_details iad  LEFT OUTER JOIN       
       inventory_adjustment_info iai ON iad.inv_adjustment_info_id=iai.id AND   iad.branch_id=iai.BranchAutoId 
       
       WHERE   iad.is_active='Y' AND iad.is_delete='N'  
                AND iai.date >= '".$startDate."'
                AND iai.date <= '".$endDate."'   
       
       GROUP BY iad.product_id, iad.branch_id     
        ) Inv_Adj ON Inv_Adj.product_id=BaseT.product_id AND   Inv_Adj.branch_id=BaseT.branch_id 
        
        
        
            ) Tol


 WHERE 1=1 
-- AND category_id=1 AND brand_id=1 AND product_id=1
";

$query4="SELECT 
IFNULL(tb_subcategory.SubCatName,'NA') SubCatName,
IFNULL(tb_model.Model,'NA') Model_name,
IFNULL(tb_size.Size,'NA') SizeName,
IFNULL(tb_color.Color,'NA') ColorName,
Tol.branch_id,Tol.branch_name,
 
  Tol.product_id, Tol.category_id, Tol.unit_id, Tol.brand_id,
    Tol.BrandName, Tol.productName,   Tol.CategoryName, Tol.unitTtile,

IFNULL(Tol.OP_quantity,0) OP_quantity,
IFNULL(Tol.OP_UPrice,0) OP_UPrice ,
IFNULL(Tol.OP_Amount,0) OP_Amount,
IFNULL(Tol.Pur_quantity,0) Pur_quantity,
IFNULL(Tol.Pur_UPrice,0) Pur_UPrice,
IFNULL(Tol.Pur_Amount,0) Pur_Amount,
IFNULL(Tol.S_quantity,0) S_quantity,
IFNULL(Tol.S_UPrice,0) S_UPrice,
IFNULL(Tol.S_Amount,0) S_Amount,

IFNULL(IFNULL(Tol.OP_quantity,0)+IFNULL(Tol.Pur_quantity,0)-IFNULL(Tol.S_quantity,0),0) C_quantity ,

IFNULL(IFNULL((IFNULL(Tol.OP_Amount,0)+IFNULL(Tol.Pur_Amount,0)) ,0)/NULLIF(((IFNULL(Tol.OP_quantity,0)+IFNULL(Tol.Pur_quantity,0))),0),0) C_UPrice,
 

 IFNULL(((IFNULL(IFNULL(Tol.OP_quantity,0)+IFNULL(Tol.Pur_quantity,0)-IFNULL(Tol.S_quantity,0),0))*
 (IFNULL(IFNULL((IFNULL(Tol.OP_Amount,0)+IFNULL(Tol.Pur_Amount,0)) ,0)/NULLIF(((IFNULL(Tol.OP_quantity,0)+IFNULL(Tol.Pur_quantity,0))),0),0))),0) C_Amount

FROM


(

SELECT BaseT.product_id,BaseT.category_id,BaseT.unit_id,BaseT.brand_id, 
brand.brandName BrandName,BaseT.productName, title CategoryName,unit.unitTtile,
BaseT.branch_id,branch.branch_name,

  IFNULL(OP_quantity,0) OP_quantity,
IFNULL(OP_UPrice,0) OP_UPrice 
,IFNULL(OP_Amount,0) OP_Amount,

IFNULL((IFNULL(Pur_quantity,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0)),0) Pur_quantity ,

IFNULL(((IFNULL((IFNULL(Pur_Amount,0)+ IFNULL(Srt_Amount,0) + IFNULL(INV_IN_Amount,0) ),0))/
NULLIF((IFNULL((IFNULL(Pur_quantity,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0) ),0)),0)),0)  Pur_UPrice,



IFNULL((IFNULL(Pur_Amount,0)+ IFNULL(Srt_Amount,0)+IFNULL(INV_IN_Amount,0)),0) Pur_Amount,



IFNULL((IFNULL(S_quantity,0) + IFNULL(INV_out_qty,0)),0) S_quantity ,


IFNULL(((IFNULL((IFNULL(S_Amount,0) + IFNULL(INV_OUT_Amount,0)),0))

/NULLIF((IFNULL((IFNULL(S_quantity,0) + IFNULL(INV_out_qty,0)),0)),0)),0) S_UPrice,
IFNULL((IFNULL(S_Amount,0) + IFNULL(INV_OUT_Amount,0)),0) S_Amount 
 

FROM 
 (
SELECT product.product_id,T2.branch_id, product.productName,product.category_id ,product.unit_id,product.brand_id
  FROM  
 (SELECT product_id,branch_id  FROM   purchase_details Where 
 ".$branch_id."
  union  
  SELECT product_id,branch_id FROM   purchase_return_details  Where 
 ".$branch_id."
 
  union
SELECT inventory_adjustment_details.product_id as product_id,inventory_adjustment_details.BranchAutoId AS branch_id FROM inventory_adjustment_details WHERE 
".$inventory_adjustment_details_branch_id."
-- inventory_adjustment_details.BranchAutoId
  )   
    T2 LEFT OUTER JOIN 
   product  
  ON product.product_id=T2.product_id  WHERE product.category_id<>1) BaseT LEFT OUTER JOIN
                      productcategory ON BaseT.category_id = productcategory.category_id LEFT OUTER JOIN
                      unit ON BaseT.unit_id = unit.unit_id LEFT OUTER JOIN
                      brand ON BaseT.brand_id = brand.brandId LEFT OUTER JOIN 
     branch ON BaseT.branch_id=branch.branch_id LEFT OUTER JOIN 
                      
  (
SELECT BaseT.product_id, BaseT.category_id , BaseT.unit_id, BaseT.brand_id,
 brandName BrandName,productName, title CategoryName,unitTtile,
BaseT.branch_id,branch.branch_name,

(IFNULL((IFNULL(OP_quantity,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0)),0)) -
(IFNULL((IFNULL(S_quantity,0) + IFNULL(INV_out_qty,0)),0))  
OP_quantity  ,

IFNULL((IFNULL(OP_Amount,0)+ IFNULL(Srt_Amount,0) + IFNULL(INV_IN_Amount,0)),0) / NULLIF((
IFNULL((IFNULL(OP_quantity,0)+ IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0)),0)),0)   
OP_UPrice ,

  IFNULL(((IFNULL((IFNULL(OP_quantity,0) + IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0)),0)) -
(IFNULL((IFNULL(S_quantity,0) + IFNULL(INV_out_qty,0)),0)))*(IFNULL((IFNULL(OP_Amount,0)+ 
IFNULL(Srt_Amount,0) + IFNULL(INV_IN_Amount,0)),0) / NULLIF((
IFNULL((IFNULL(OP_quantity,0)+ IFNULL(Srt_quantity,0) + IFNULL(INV_in_qty,0)),0)),0)) ,0)
 OP_Amount 
  
FROM 

(
SELECT product.product_id,T2.branch_id, product.productName,product.category_id ,product.unit_id,product.brand_id
  FROM  
 (SELECT product_id,branch_id  FROM   purchase_details Where 
 ".$branch_id."
  union  
  SELECT product_id,branch_id FROM   purchase_return_details  Where 
 ".$branch_id."
 
 union
SELECT inventory_adjustment_details.product_id as product_id,inventory_adjustment_details.BranchAutoId AS branch_id FROM inventory_adjustment_details WHERE 
".$inventory_adjustment_details_branch_id."
-- inventory_adjustment_details.BranchAutoId
  )   
    T2 LEFT OUTER JOIN 
   product  
  ON product.product_id=T2.product_id  WHERE product.category_id<>1) BaseT LEFT OUTER JOIN
                      productcategory ON BaseT.category_id = productcategory.category_id LEFT OUTER JOIN
                      unit ON BaseT.unit_id = unit.unit_id LEFT OUTER JOIN
                      brand ON BaseT.brand_id = brand.brandId LEFT OUTER JOIN 
     branch ON BaseT.branch_id=branch.branch_id LEFT OUTER JOIN 

(
 
SELECT   
       pd.product_id  , pd.branch_id, 
       
        SUM(pd.quantity)  OP_quantity
      , SUM(pd.quantity * pd.unit_price) / NULLIF(SUM(pd.quantity),0) AS OP_UPrice       
     ,(SUM(pd.quantity * pd.unit_price) / NULLIF(SUM(pd.quantity),0))*(SUM(pd.quantity) )  OP_Amount
       
  FROM purchase_details pd  LEFT OUTER JOIN
       
       purchase_invoice_info pii ON pd.purchase_invoice_id=pii.purchase_invoice_id AND pd.branch_id=pii.branch_id
       
       WHERE  pd.is_active='Y' AND pd.is_delete='N' AND pii.invoice_date <  '".$startDate."'  
       
       GROUP BY pd.product_id  ,pd.branch_id
       
        
       ) opening  ON opening.product_id=BaseT.product_id AND opening.branch_id=BaseT.branch_id  LEFT  OUTER JOIN
 

       
    (
SELECT   
        sr.product_id, sr.branch_id, SUM(sr.return_quantity)  Srt_quantity
      , SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0) AS Srt_UPrice       
     ,(SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0))*(SUM(sr.return_quantity) )  Srt_Amount
       
  FROM sales_return sr   
       
       WHERE  sr.is_active='Y' AND sr.is_delete='N'    
       AND sr.return_date < '".$startDate."'  
         
       
       GROUP BY sr.product_id ,sr.branch_id
       
      
       ) sales_Ret ON  BaseT.product_id= sales_Ret.product_id AND BaseT.branch_id= sales_Ret.branch_id LEFT  OUTER JOIN   
       (
SELECT   
       sd.product_id,sd.branch_id,   SUM(sd.quantity)  S_quantity
      , SUM(sd.quantity * sd.unit_price) / NULLIF(SUM(sd.quantity),0)
       AS S_UPrice       
     ,(SUM(sd.quantity * sd.unit_price) / NULLIF(SUM(sd.quantity),0))*(SUM(sd.quantity) )  S_Amount
       
  FROM sales_details sd  LEFT OUTER JOIN
       
       sales_invoice_info sii ON sd.sales_invoice_id=sii.sales_invoice_id AND sd.branch_id=sii.branch_id 
       
       WHERE   sd.is_active='Y' AND sd.is_delete='N'  
 AND sii.invoice_date < '".$startDate."'
       
       GROUP BY sd.product_id ,sd.branch_id
       
       
        ) Sales ON Sales.product_id=BaseT.product_id AND Sales.branch_id=BaseT.branch_id 
        LEFT  OUTER JOIN   
       (
SELECT   
       iad.product_id,   iad.BranchAutoId,
       SUM(iad.in_qty)  INV_in_qty,  
       SUM(iad.out_qty)  INV_out_qty, 
       
        SUM(iad.in_qty * iad.unit_price) / NULLIF(SUM(iad.in_qty),0) AS INV_IN_UPrice  ,     
        SUM(iad.out_qty * iad.unit_price) / NULLIF(SUM(iad.out_qty),0) AS INV_OUT_UPrice ,  
      
          
      (SUM(iad.in_qty * iad.unit_price) / NULLIF(SUM(iad.in_qty),0))*(SUM(iad.in_qty))  INV_IN_Amount,
      (SUM(iad.out_qty * iad.unit_price) / NULLIF(SUM(iad.out_qty),0))*( SUM(iad.out_qty))  INV_OUT_Amount 
       
  FROM inventory_adjustment_details iad  LEFT OUTER JOIN
       
       inventory_adjustment_info iai ON iad.inv_adjustment_info_id=iai.id AND iad.BranchAutoId=iai.BranchAutoId
       
       WHERE   iad.is_active='Y' AND iad.is_delete='N'  
 AND iai.date < '".$startDate."'
       
       GROUP BY iad.product_id  ,iad.branch_id   
        ) Inv_Adj ON Inv_Adj.product_id=BaseT.product_id  AND Inv_Adj.BranchAutoId=BaseT.branch_id
--         GROUP BY BaseT.product_id,BaseT.BranchAutoId
        

) Opening_All   
          
 ON Opening_All.product_id=BaseT.product_id  and Opening_All.branch_id=BaseT.branch_id   LEFT  OUTER JOIN
 
 (
SELECT   
       pd.product_id,pd.branch_id,   SUM(pd.quantity)  Pur_quantity
      , SUM(pd.quantity * pd.unit_price) / NULLIF(SUM(pd.quantity),0) AS Pur_UPrice       
     ,(SUM(pd.quantity * pd.unit_price) / NULLIF(SUM(pd.quantity),0))*(SUM(pd.quantity) )  Pur_Amount
       
  FROM purchase_details pd  LEFT OUTER JOIN      
       purchase_invoice_info pii ON pd.purchase_invoice_id=pii.purchase_invoice_id  AND pd.branch_id=pii.branch_id       
       WHERE  pd.is_active='Y' AND pd.is_delete='N'    
       AND pii.invoice_date >=  '".$startDate."' 
       AND pii.invoice_date <= '".$endDate."'         
       
       GROUP BY pd.product_id      ,pd.branch_id
      
       ) purchase ON  BaseT.product_id= purchase.product_id AND BaseT.branch_id= purchase.branch_id   LEFT  OUTER JOIN
       
    (
SELECT   
        sr.product_id,sr.branch_id , SUM(sr.return_quantity)  Srt_quantity
      , SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0) AS Srt_UPrice       
     ,(SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0))*(SUM(sr.return_quantity) )  Srt_Amount
       
  FROM sales_return sr   
       
       WHERE  sr.is_active='Y' AND sr.is_delete='N'    
       AND sr.return_date >= '".$startDate."'
       AND sr.return_date <= '".$endDate."'  
         
       
       GROUP BY sr.product_id,sr.branch_id 
       
      
       ) sales_Ret ON  BaseT.product_id= sales_Ret.product_id AND  BaseT.branch_id= sales_Ret.branch_id LEFT  OUTER JOIN   
       (
SELECT   
       sd.product_id,sd.branch_id,   SUM(sd.quantity)  S_quantity
      , SUM(sd.quantity * sd.unit_price) / NULLIF(SUM(sd.quantity),0) AS S_UPrice       
     ,(SUM(sd.quantity * sd.unit_price) / NULLIF(SUM(sd.quantity),0))*(SUM(sd.quantity) )  S_Amount
       
  FROM sales_details sd  LEFT OUTER JOIN
       
       sales_invoice_info sii ON sd.sales_invoice_id=sii.sales_invoice_id AND sd.branch_id=sii.branch_id
       
       WHERE   sd.is_active='Y' AND sd.is_delete='N'  
 AND sii.invoice_date >= '".$startDate."'
AND sii.invoice_date <= '".$endDate."' 
       
       GROUP BY sd.product_id ,sd.branch_id
       
       
        ) Sales ON Sales.product_id=BaseT.product_id AND Sales.branch_id=BaseT.branch_id 
        LEFT  OUTER JOIN   
       (
SELECT   
       iad.product_id,   iad.BranchAutoId, 
       SUM(iad.in_qty)  INV_in_qty,  
       SUM(iad.out_qty)  INV_out_qty, 
       
        SUM(iad.in_qty * iad.unit_price) / NULLIF(SUM(iad.in_qty),0) AS INV_IN_UPrice  ,     
        SUM(iad.out_qty * iad.unit_price) / NULLIF(SUM(iad.out_qty),0) AS INV_OUT_UPrice ,  
      
          
      (SUM(iad.in_qty * iad.unit_price) / NULLIF(SUM(iad.in_qty),0))*(SUM(iad.in_qty))  INV_IN_Amount,
      (SUM(iad.out_qty * iad.unit_price) / NULLIF(SUM(iad.out_qty),0))*( SUM(iad.out_qty))  INV_OUT_Amount 
       
  FROM inventory_adjustment_details iad  LEFT OUTER JOIN       
       inventory_adjustment_info iai ON iad.inv_adjustment_info_id=iai.id AND   iad.BranchAutoId=iai.BranchAutoId 
       
       WHERE   iad.is_active='Y' AND iad.is_delete='N'  
                AND iai.date >= '".$startDate."'
                AND iai.date <= '".$endDate."'    
       
       GROUP BY iad.product_id, iad.BranchAutoId     
        ) Inv_Adj ON Inv_Adj.product_id=BaseT.product_id AND   Inv_Adj.BranchAutoId=BaseT.branch_id 
        
        
        
            ) Tol
LEFT JOIN product ON product.product_id=Tol.product_id
LEFT JOIN tb_subcategory ON tb_subcategory.SubCatID=product.subcategoryID
LEFT JOIN tb_color ON tb_color.ColorID=product.colorID
LEFT JOIN tb_size ON tb_size.SizeID=product.sizeID
LEFT JOIN tb_model ON tb_model.ModelID=product.modelID

 WHERE 1=1 ";


        if($productCatagory !='all'){
            $query4.=" AND Tol.category_id=".$productCatagory;
        }
        if($productBrand !='all'){
            $query4.=" AND Tol.brand_id=".$productBrand;
        }
        if($productId !='all'){
            $query4.=" AND Tol.product_id=".$productId;
        }
        if($subcategory !='all'){
            $query4.=" AND tb_subcategory.SubCatID=".$subcategory;
        }
        if($size !='all'){
            $query4.=" AND tb_size.SizeID=".$size;
        }
        if($color !='all'){
            $query4.=" AND tb_color.ColorID=".$color;
        }
        if($model_id !='all'){
            $query4.=" AND tb_model.ModelID=".$model_id;
        }


        $query4.=" ORDER BY  Tol.branch_name,Tol.product_id,tb_subcategory.SubCatID,tb_color.ColorID,tb_size.SizeID";
        log_message('error','this is stock report'.print_r($query4,true));
        $query = $this->db->query($query4);
        $result = $query->result();
        if ($this->business_type == "MOTORCYCLE") {
            foreach ($result as $key => $value) {
                $array[$value->branch_name][$value->CategoryName][$value->BrandName][$value->Model_name][] = $value;
            }
        }else{
            foreach ($result as $key => $value) {
                $array[$value->branch_name][$value->CategoryName][$value->BrandName][] = $value;
            }
        }
        return $array;

    }*/




}