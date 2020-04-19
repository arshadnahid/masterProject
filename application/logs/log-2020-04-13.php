INFO - 2020-04-13 13:50:38 --> Config Class Initialized
INFO - 2020-04-13 13:50:38 --> Hooks Class Initialized
DEBUG - 2020-04-13 13:50:38 --> UTF-8 Support Enabled
INFO - 2020-04-13 13:50:38 --> Utf8 Class Initialized
INFO - 2020-04-13 13:50:38 --> URI Class Initialized
INFO - 2020-04-13 13:50:38 --> Router Class Initialized
INFO - 2020-04-13 13:50:38 --> Output Class Initialized
INFO - 2020-04-13 13:50:38 --> Security Class Initialized
DEBUG - 2020-04-13 13:50:38 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-13 13:50:38 --> Input Class Initialized
INFO - 2020-04-13 13:50:38 --> Language Class Initialized
INFO - 2020-04-13 13:50:38 --> Loader Class Initialized
INFO - 2020-04-13 13:50:38 --> Helper loaded: url_helper
INFO - 2020-04-13 13:50:38 --> Helper loaded: file_helper
INFO - 2020-04-13 13:50:38 --> Helper loaded: utility_helper
INFO - 2020-04-13 13:50:38 --> Helper loaded: unit_helper
INFO - 2020-04-13 13:50:38 --> Helper loaded: multi_language_helper
INFO - 2020-04-13 13:50:38 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-13 13:50:38 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-13 13:50:38 --> Helper loaded: site_helper
INFO - 2020-04-13 13:50:38 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-13 13:50:38 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-13 13:50:38 --> Database Driver Class Initialized
INFO - 2020-04-13 13:50:38 --> Email Class Initialized
DEBUG - 2020-04-13 13:50:38 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-13 13:50:38 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-13 13:50:38 --> Helper loaded: form_helper
INFO - 2020-04-13 13:50:38 --> Form Validation Class Initialized
INFO - 2020-04-13 13:50:38 --> Controller Class Initialized
INFO - 2020-04-13 13:50:38 --> Model "Common_model" initialized
INFO - 2020-04-13 13:50:38 --> Model "Finane_Model" initialized
INFO - 2020-04-13 13:50:38 --> Model "Inventory_Model" initialized
INFO - 2020-04-13 13:50:38 --> Model "Sales_Model" initialized
INFO - 2020-04-13 13:50:38 --> Database Driver Class Initialized
INFO - 2020-04-13 13:50:38 --> Helper loaded: language_helper
INFO - 2020-04-13 13:50:38 --> Language file loaded: language/english/content_lang.php
ERROR - 2020-04-13 13:50:38 --> this is stock reportSELECT 
 
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
       
       WHERE  pd.is_active='Y' AND pd.is_delete='N' AND pii.invoice_date < '2019-04-13'  
       
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
       AND pii.invoice_date < '2019-04-13' 
               
       
       GROUP BY pd.product_id      
      
       ) purchase ON  BaseT.product_id= purchase.product_id LEFT  OUTER JOIN
       
    (
SELECT   
        sr.product_id,  SUM(sr.return_quantity)  Srt_quantity
      , SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0) AS Srt_UPrice       
     ,(SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0))*(SUM(sr.return_quantity) )  Srt_Amount
       
  FROM sales_return sr   
       
       WHERE  sr.is_active='Y' AND sr.is_delete='N'    
       AND sr.return_date < '2019-04-13'  
         
       
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
 AND sii.invoice_date < '2019-04-13'  
       
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
 AND iai.date < '2019-04-13' 
       
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
       AND pii.invoice_date >= '2019-04-13' 
       AND pii.invoice_date <= '2020-03-07'          
       
       GROUP BY pd.product_id      
      
       ) purchase ON  BaseT.product_id= purchase.product_id LEFT  OUTER JOIN
       
    (
SELECT   
        sr.product_id,  SUM(sr.return_quantity)  Srt_quantity
      , SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0) AS Srt_UPrice       
     ,(SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0))*(SUM(sr.return_quantity) )  Srt_Amount
       
  FROM sales_return sr   
       
       WHERE  sr.is_active='Y' AND sr.is_delete='N'    
       AND sr.return_date >= '2019-04-13'
       AND sr.return_date <= '2020-03-07'  
         
       
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
 AND sii.invoice_date >= '2019-04-13'
AND sii.invoice_date <= '2020-03-07'  
       
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
 AND iai.date >= '2019-04-13'
AND iai.date <= '2020-03-07'   
       
       GROUP BY iad.product_id     
        ) Inv_Adj ON Inv_Adj.product_id=BaseT.product_id 
        
        
        
            ) Tol WHERE 1=1
ERROR - 2020-04-13 13:50:38 --> query2 :SELECT 
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
 AND purchase_invoice_info.invoice_date < '2019-04-13'
  
 

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
  AND purchase_invoice_info.invoice_date < '2019-04-13'   
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
 AND sales_invoice_info.invoice_date < '2019-04-13'
  
 

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
AND purchase_invoice_info.invoice_date < '2019-04-13'
  

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
AND purchase_invoice_info.invoice_date < '2019-04-13'
  

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
AND sales_invoice_info.invoice_date < '2019-04-13'
     

GROUP BY   sales_return_details.product_id) Ret_Sales ON   BaseT.product_id= Ret_Sales.product_id  LEFT  OUTER JOIN  


/*END ReturnSales*/
 
 (
SELECT   
        sr.product_id,  SUM(sr.return_quantity)  Srt_quantity
      , SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0) AS Srt_UPrice       
     ,(SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0))*(SUM(sr.return_quantity) )  Srt_Amount
       
  FROM sales_return sr   
       
       WHERE  sr.is_active='Y' AND sr.is_delete='N'    
       AND sr.return_date < '2019-04-13'
         
       
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
 AND iai.date < '2019-04-13'
       
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


AND purchase_invoice_info.invoice_date  >= '2019-04-13'
 AND purchase_invoice_info.invoice_date  <= '2020-03-07'   
 
 
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


AND purchase_invoice_info.invoice_date  >= '2019-04-13'
 AND purchase_invoice_info.invoice_date  <= '2020-03-07'    
 
    
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

AND sales_invoice_info.invoice_date  >= '2019-04-13'
 AND sales_invoice_info.invoice_date  <= '2020-03-07'    
 
 
  
 

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


AND  purchase_invoice_info.invoice_date  >= '2019-04-13'
 AND  purchase_invoice_info.invoice_date <= '2020-03-07'  
 
 
  

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

AND  purchase_invoice_info.invoice_date  >= '2019-04-13'
 AND  purchase_invoice_info.invoice_date  <= '2020-03-07'  
 
 
  

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
AND  sales_invoice_info.invoice_date  >= '2019-04-13'
 AND  sales_invoice_info.invoice_date  <= '2020-03-07' 
 
     

GROUP BY   sales_return_details.product_id) Ret_Sales ON   BaseT.product_id= Ret_Sales.product_id  LEFT  OUTER JOIN  


/*END ReturnSales*/
 
 (
SELECT   
        sr.product_id,  SUM(sr.return_quantity)  Srt_quantity
      , SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0) AS Srt_UPrice       
     ,(SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0))*(SUM(sr.return_quantity) )  Srt_Amount
       
  FROM sales_return sr   
       
       WHERE  sr.is_active='Y' AND sr.is_delete='N'    
        
         AND sr.return_date  >= '2019-04-13'
 AND sr.return_date  <= '2020-03-07' 
       
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
 AND iai.date >= '2019-04-13'
AND iai.date <= '2020-03-07' 
       
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
       
       WHERE  pd.is_active='Y' AND pd.is_delete='N' AND pii.invoice_date < '2019-04-13'   
       
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
       AND pii.invoice_date < '2019-04-13' 
               
       
       GROUP BY pd.product_id      
      
       ) purchase ON  BaseT.product_id= purchase.product_id LEFT  OUTER JOIN
       
    (
SELECT   
        sr.product_id,  SUM(sr.return_quantity)  Srt_quantity
      , SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0) AS Srt_UPrice       
     ,(SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0))*(SUM(sr.return_quantity) )  Srt_Amount
       
  FROM sales_return sr   
       
       WHERE  sr.is_active='Y' AND sr.is_delete='N'    
       AND sr.return_date < '2019-04-13' 
         
       
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
 AND sii.invoice_date < '2019-04-13'  
       
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
 AND iai.date < '2019-04-13'
       
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
       AND pii.invoice_date >= '2019-04-13' 
       AND pii.invoice_date  <= '2020-03-07'          
       
       GROUP BY pd.product_id      
      
       ) purchase ON  BaseT.product_id= purchase.product_id LEFT  OUTER JOIN
       
    (
SELECT   
        sr.product_id,  SUM(sr.return_quantity)  Srt_quantity
      , SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0) AS Srt_UPrice       
     ,(SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0))*(SUM(sr.return_quantity) )  Srt_Amount
       
  FROM sales_return sr   
       
       WHERE  sr.is_active='Y' AND sr.is_delete='N'    
       AND sr.return_date >= '2019-04-13'
       AND sr.return_date  <= '2020-03-07' 
         
       
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
 AND sii.invoice_date >= '2019-04-13'
AND sii.invoice_date  <= '2020-03-07' 
       
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
 AND iai.date >= '2019-04-13'
AND iai.date  <= '2020-03-07' 
       
       GROUP BY iad.product_id     
        ) Inv_Adj ON Inv_Adj.product_id=BaseT.product_id 
        
        
        
            ) Tol on Tol.product_id=pp2.product_id


WHERE 1=1
INFO - 2020-04-13 13:50:38 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/inventory/report/current_stock_report.php
ERROR - 2020-04-13 13:50:38 --> Could not find the language line "Sales_Lpg_Invoice_add"
ERROR - 2020-04-13 13:50:38 --> Could not find the language line "Report"
ERROR - 2020-04-13 13:50:38 --> Could not find the language line "Product_Wise_Sales"
ERROR - 2020-04-13 13:50:38 --> Could not find the language line "Report"
ERROR - 2020-04-13 13:50:38 --> Could not find the language line "Day_Book"
ERROR - 2020-04-13 13:50:38 --> Could not find the language line "Employee"
ERROR - 2020-04-13 13:50:38 --> Could not find the language line "Branch_Info"
ERROR - 2020-04-13 13:50:38 --> Could not find the language line "Bank_Account_Info"
ERROR - 2020-04-13 13:50:38 --> Could not find the language line "Purchases_lpg_add"
ERROR - 2020-04-13 13:50:38 --> Could not find the language line "2019"
ERROR - 2020-04-13 13:50:38 --> Could not find the language line "Report"
ERROR - 2020-04-13 13:50:38 --> Could not find the language line "Purchases_Invoice"
INFO - 2020-04-13 13:50:38 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/masterTemplate.php
INFO - 2020-04-13 13:50:38 --> Final output sent to browser
DEBUG - 2020-04-13 13:50:38 --> Total execution time: 0.6279
INFO - 2020-04-13 13:53:16 --> Config Class Initialized
INFO - 2020-04-13 13:53:16 --> Hooks Class Initialized
DEBUG - 2020-04-13 13:53:16 --> UTF-8 Support Enabled
INFO - 2020-04-13 13:53:16 --> Utf8 Class Initialized
INFO - 2020-04-13 13:53:16 --> URI Class Initialized
INFO - 2020-04-13 13:53:16 --> Router Class Initialized
INFO - 2020-04-13 13:53:16 --> Output Class Initialized
INFO - 2020-04-13 13:53:16 --> Security Class Initialized
DEBUG - 2020-04-13 13:53:16 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-13 13:53:16 --> Input Class Initialized
INFO - 2020-04-13 13:53:16 --> Language Class Initialized
INFO - 2020-04-13 13:53:16 --> Loader Class Initialized
INFO - 2020-04-13 13:53:17 --> Helper loaded: url_helper
INFO - 2020-04-13 13:53:17 --> Helper loaded: file_helper
INFO - 2020-04-13 13:53:17 --> Helper loaded: utility_helper
INFO - 2020-04-13 13:53:17 --> Helper loaded: unit_helper
INFO - 2020-04-13 13:53:17 --> Helper loaded: multi_language_helper
INFO - 2020-04-13 13:53:17 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-13 13:53:17 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-13 13:53:17 --> Helper loaded: site_helper
INFO - 2020-04-13 13:53:17 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-13 13:53:17 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-13 13:53:17 --> Database Driver Class Initialized
INFO - 2020-04-13 13:53:17 --> Email Class Initialized
DEBUG - 2020-04-13 13:53:17 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-13 13:53:17 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-13 13:53:17 --> Helper loaded: form_helper
INFO - 2020-04-13 13:53:17 --> Form Validation Class Initialized
INFO - 2020-04-13 13:53:17 --> Controller Class Initialized
INFO - 2020-04-13 13:53:17 --> Model "Common_model" initialized
INFO - 2020-04-13 13:53:17 --> Model "Finane_Model" initialized
INFO - 2020-04-13 13:53:17 --> Model "Inventory_Model" initialized
INFO - 2020-04-13 13:53:17 --> Model "Sales_Model" initialized
INFO - 2020-04-13 13:53:17 --> Database Driver Class Initialized
INFO - 2020-04-13 13:53:17 --> Helper loaded: language_helper
INFO - 2020-04-13 13:53:17 --> Language file loaded: language/english/content_lang.php
ERROR - 2020-04-13 13:53:17 --> this is stock reportSELECT 
 
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
       
       WHERE  pd.is_active='Y' AND pd.is_delete='N' AND pii.invoice_date < '2019-04-13'  
       
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
       AND pii.invoice_date < '2019-04-13' 
               
       
       GROUP BY pd.product_id      
      
       ) purchase ON  BaseT.product_id= purchase.product_id LEFT  OUTER JOIN
       
    (
SELECT   
        sr.product_id,  SUM(sr.return_quantity)  Srt_quantity
      , SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0) AS Srt_UPrice       
     ,(SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0))*(SUM(sr.return_quantity) )  Srt_Amount
       
  FROM sales_return sr   
       
       WHERE  sr.is_active='Y' AND sr.is_delete='N'    
       AND sr.return_date < '2019-04-13'  
         
       
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
 AND sii.invoice_date < '2019-04-13'  
       
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
 AND iai.date < '2019-04-13' 
       
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
       AND pii.invoice_date >= '2019-04-13' 
       AND pii.invoice_date <= '2020-03-07'          
       
       GROUP BY pd.product_id      
      
       ) purchase ON  BaseT.product_id= purchase.product_id LEFT  OUTER JOIN
       
    (
SELECT   
        sr.product_id,  SUM(sr.return_quantity)  Srt_quantity
      , SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0) AS Srt_UPrice       
     ,(SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0))*(SUM(sr.return_quantity) )  Srt_Amount
       
  FROM sales_return sr   
       
       WHERE  sr.is_active='Y' AND sr.is_delete='N'    
       AND sr.return_date >= '2019-04-13'
       AND sr.return_date <= '2020-03-07'  
         
       
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
 AND sii.invoice_date >= '2019-04-13'
AND sii.invoice_date <= '2020-03-07'  
       
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
 AND iai.date >= '2019-04-13'
AND iai.date <= '2020-03-07'   
       
       GROUP BY iad.product_id     
        ) Inv_Adj ON Inv_Adj.product_id=BaseT.product_id 
        
        
        
            ) Tol WHERE 1=1
ERROR - 2020-04-13 13:53:17 --> query2 :SELECT 
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
 AND purchase_invoice_info.invoice_date < '2019-04-13'
  
 

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
  AND purchase_invoice_info.invoice_date < '2019-04-13'   
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
 AND sales_invoice_info.invoice_date < '2019-04-13'
  
 

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
AND purchase_invoice_info.invoice_date < '2019-04-13'
  

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
AND purchase_invoice_info.invoice_date < '2019-04-13'
  

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
AND sales_invoice_info.invoice_date < '2019-04-13'
     

GROUP BY   sales_return_details.product_id) Ret_Sales ON   BaseT.product_id= Ret_Sales.product_id  LEFT  OUTER JOIN  


/*END ReturnSales*/
 
 (
SELECT   
        sr.product_id,  SUM(sr.return_quantity)  Srt_quantity
      , SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0) AS Srt_UPrice       
     ,(SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0))*(SUM(sr.return_quantity) )  Srt_Amount
       
  FROM sales_return sr   
       
       WHERE  sr.is_active='Y' AND sr.is_delete='N'    
       AND sr.return_date < '2019-04-13'
         
       
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
 AND iai.date < '2019-04-13'
       
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


AND purchase_invoice_info.invoice_date  >= '2019-04-13'
 AND purchase_invoice_info.invoice_date  <= '2020-03-07'   
 
 
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


AND purchase_invoice_info.invoice_date  >= '2019-04-13'
 AND purchase_invoice_info.invoice_date  <= '2020-03-07'    
 
    
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

AND sales_invoice_info.invoice_date  >= '2019-04-13'
 AND sales_invoice_info.invoice_date  <= '2020-03-07'    
 
 
  
 

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


AND  purchase_invoice_info.invoice_date  >= '2019-04-13'
 AND  purchase_invoice_info.invoice_date <= '2020-03-07'  
 
 
  

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

AND  purchase_invoice_info.invoice_date  >= '2019-04-13'
 AND  purchase_invoice_info.invoice_date  <= '2020-03-07'  
 
 
  

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
AND  sales_invoice_info.invoice_date  >= '2019-04-13'
 AND  sales_invoice_info.invoice_date  <= '2020-03-07' 
 
     

GROUP BY   sales_return_details.product_id) Ret_Sales ON   BaseT.product_id= Ret_Sales.product_id  LEFT  OUTER JOIN  


/*END ReturnSales*/
 
 (
SELECT   
        sr.product_id,  SUM(sr.return_quantity)  Srt_quantity
      , SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0) AS Srt_UPrice       
     ,(SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0))*(SUM(sr.return_quantity) )  Srt_Amount
       
  FROM sales_return sr   
       
       WHERE  sr.is_active='Y' AND sr.is_delete='N'    
        
         AND sr.return_date  >= '2019-04-13'
 AND sr.return_date  <= '2020-03-07' 
       
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
 AND iai.date >= '2019-04-13'
AND iai.date <= '2020-03-07' 
       
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
       
       WHERE  pd.is_active='Y' AND pd.is_delete='N' AND pii.invoice_date < '2019-04-13'   
       
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
       AND pii.invoice_date < '2019-04-13' 
               
       
       GROUP BY pd.product_id      
      
       ) purchase ON  BaseT.product_id= purchase.product_id LEFT  OUTER JOIN
       
    (
SELECT   
        sr.product_id,  SUM(sr.return_quantity)  Srt_quantity
      , SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0) AS Srt_UPrice       
     ,(SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0))*(SUM(sr.return_quantity) )  Srt_Amount
       
  FROM sales_return sr   
       
       WHERE  sr.is_active='Y' AND sr.is_delete='N'    
       AND sr.return_date < '2019-04-13' 
         
       
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
 AND sii.invoice_date < '2019-04-13'  
       
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
 AND iai.date < '2019-04-13'
       
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
       AND pii.invoice_date >= '2019-04-13' 
       AND pii.invoice_date  <= '2020-03-07'          
       
       GROUP BY pd.product_id      
      
       ) purchase ON  BaseT.product_id= purchase.product_id LEFT  OUTER JOIN
       
    (
SELECT   
        sr.product_id,  SUM(sr.return_quantity)  Srt_quantity
      , SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0) AS Srt_UPrice       
     ,(SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0))*(SUM(sr.return_quantity) )  Srt_Amount
       
  FROM sales_return sr   
       
       WHERE  sr.is_active='Y' AND sr.is_delete='N'    
       AND sr.return_date >= '2019-04-13'
       AND sr.return_date  <= '2020-03-07' 
         
       
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
 AND sii.invoice_date >= '2019-04-13'
AND sii.invoice_date  <= '2020-03-07' 
       
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
 AND iai.date >= '2019-04-13'
AND iai.date  <= '2020-03-07' 
       
       GROUP BY iad.product_id     
        ) Inv_Adj ON Inv_Adj.product_id=BaseT.product_id 
        
        
        
            ) Tol on Tol.product_id=pp2.product_id


WHERE 1=1
INFO - 2020-04-13 13:53:17 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/inventory/report/current_stock_report.php
ERROR - 2020-04-13 13:53:17 --> Could not find the language line "Sales_Lpg_Invoice_add"
ERROR - 2020-04-13 13:53:17 --> Could not find the language line "Report"
ERROR - 2020-04-13 13:53:17 --> Could not find the language line "Product_Wise_Sales"
ERROR - 2020-04-13 13:53:17 --> Could not find the language line "Report"
ERROR - 2020-04-13 13:53:17 --> Could not find the language line "Day_Book"
ERROR - 2020-04-13 13:53:17 --> Could not find the language line "Employee"
ERROR - 2020-04-13 13:53:17 --> Could not find the language line "Branch_Info"
ERROR - 2020-04-13 13:53:17 --> Could not find the language line "Bank_Account_Info"
ERROR - 2020-04-13 13:53:17 --> Could not find the language line "Purchases_lpg_add"
ERROR - 2020-04-13 13:53:17 --> Could not find the language line "2019"
ERROR - 2020-04-13 13:53:17 --> Could not find the language line "Report"
ERROR - 2020-04-13 13:53:17 --> Could not find the language line "Purchases_Invoice"
INFO - 2020-04-13 13:53:17 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/masterTemplate.php
INFO - 2020-04-13 13:53:17 --> Final output sent to browser
DEBUG - 2020-04-13 13:53:17 --> Total execution time: 0.5909
INFO - 2020-04-13 14:46:07 --> Config Class Initialized
INFO - 2020-04-13 14:46:08 --> Hooks Class Initialized
DEBUG - 2020-04-13 14:46:08 --> UTF-8 Support Enabled
INFO - 2020-04-13 14:46:08 --> Utf8 Class Initialized
INFO - 2020-04-13 14:46:08 --> URI Class Initialized
INFO - 2020-04-13 14:46:08 --> Router Class Initialized
INFO - 2020-04-13 14:46:08 --> Output Class Initialized
INFO - 2020-04-13 14:46:08 --> Security Class Initialized
DEBUG - 2020-04-13 14:46:08 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-13 14:46:08 --> Input Class Initialized
INFO - 2020-04-13 14:46:08 --> Language Class Initialized
INFO - 2020-04-13 14:46:08 --> Loader Class Initialized
INFO - 2020-04-13 14:46:08 --> Helper loaded: url_helper
INFO - 2020-04-13 14:46:08 --> Helper loaded: file_helper
INFO - 2020-04-13 14:46:08 --> Helper loaded: utility_helper
INFO - 2020-04-13 14:46:08 --> Helper loaded: unit_helper
INFO - 2020-04-13 14:46:08 --> Helper loaded: multi_language_helper
INFO - 2020-04-13 14:46:08 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-13 14:46:08 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-13 14:46:08 --> Helper loaded: site_helper
INFO - 2020-04-13 14:46:08 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-13 14:46:08 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-13 14:46:08 --> Database Driver Class Initialized
INFO - 2020-04-13 14:46:08 --> Email Class Initialized
DEBUG - 2020-04-13 14:46:08 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-13 14:46:08 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-13 14:46:08 --> Helper loaded: form_helper
INFO - 2020-04-13 14:46:08 --> Form Validation Class Initialized
INFO - 2020-04-13 14:46:08 --> Controller Class Initialized
INFO - 2020-04-13 14:46:08 --> Model "Common_model" initialized
INFO - 2020-04-13 14:46:08 --> Model "Finane_Model" initialized
INFO - 2020-04-13 14:46:08 --> Model "Accounts_model" initialized
INFO - 2020-04-13 14:46:08 --> Model "Inventory_Model" initialized
INFO - 2020-04-13 14:46:08 --> Model "Sales_Model" initialized
INFO - 2020-04-13 14:46:08 --> Model "AccountReport_model" initialized
INFO - 2020-04-13 14:46:08 --> Database Driver Class Initialized
INFO - 2020-04-13 14:46:08 --> Helper loaded: language_helper
INFO - 2020-04-13 14:46:08 --> Language file loaded: language/english/content_lang.php
ERROR - 2020-04-13 14:46:08 --> Could not find the language line "Property_Plant_&_equipment_at_cost"
ERROR - 2020-04-13 14:46:08 --> Could not find the language line "Other_Non_Current_Assets"
ERROR - 2020-04-13 14:46:08 --> Could not find the language line "Investment_in_FDR"
ERROR - 2020-04-13 14:46:08 --> Could not find the language line "Cash_at_Bank"
ERROR - 2020-04-13 14:46:08 --> Could not find the language line "Advance_Deposits_&_Pre-Payments"
ERROR - 2020-04-13 14:46:08 --> Could not find the language line "Advance_against_salary"
ERROR - 2020-04-13 14:46:08 --> Could not find the language line "Advance_for_Expenditures"
ERROR - 2020-04-13 14:46:08 --> Could not find the language line "Advance_Tax_&_VAT"
ERROR - 2020-04-13 14:46:08 --> Could not find the language line "Reserve_&_Surplus"
ERROR - 2020-04-13 14:46:08 --> Could not find the language line "Retained_Earnings"
ERROR - 2020-04-13 14:46:08 --> Could not find the language line "Profit_Loss_Account"
ERROR - 2020-04-13 14:46:08 --> Could not find the language line "Long_Term_Loan_&_Liability-"
ERROR - 2020-04-13 14:46:08 --> Could not find the language line "Inter-Company_Loan_-_Liabilities"
ERROR - 2020-04-13 14:46:08 --> Could not find the language line "Lease_Loan_Liability"
ERROR - 2020-04-13 14:46:08 --> Could not find the language line "Other_Loans_&_Liabilities"
ERROR - 2020-04-13 14:46:08 --> Could not find the language line "Bank_Loan_&_OD_Accounts"
ERROR - 2020-04-13 14:46:08 --> Could not find the language line "Provision_for_Expenses"
ERROR - 2020-04-13 14:46:08 --> Could not find the language line "Provision_for_Income_Tex_&_VAT"
ERROR - 2020-04-13 14:46:08 --> Could not find the language line "Other_Accruals_&_Provisions"
ERROR - 2020-04-13 14:46:08 --> Could not find the language line "Income_From_Commission"
ERROR - 2020-04-13 14:46:08 --> Could not find the language line "Loader's_Payable"
ERROR - 2020-04-13 14:46:08 --> Could not find the language line "Transportation_Payable"
ERROR - 2020-04-13 14:46:08 --> Could not find the language line "Income_From_Incentive"
ERROR - 2020-04-13 14:46:08 --> Could not find the language line "Income_From_Bank_Interest"
ERROR - 2020-04-13 14:46:08 --> Could not find the language line "Income_From_FDR_&_Instruments"
ERROR - 2020-04-13 14:46:08 --> Could not find the language line "Cost_of_Goods_Product"
ERROR - 2020-04-13 14:46:08 --> Could not find the language line "Purchase_Amount"
ERROR - 2020-04-13 14:46:08 --> Could not find the language line "Godown_Rent"
ERROR - 2020-04-13 14:46:08 --> Could not find the language line "Maintenance_Cost"
ERROR - 2020-04-13 14:46:08 --> Could not find the language line "Entertainment"
ERROR - 2020-04-13 14:46:08 --> Could not find the language line "Professional_&_Legal_Fees"
ERROR - 2020-04-13 14:46:08 --> Could not find the language line "Office_Expance"
ERROR - 2020-04-13 14:46:08 --> Could not find the language line "Office_Rent_Current_Period_2020"
ERROR - 2020-04-13 14:46:08 --> Could not find the language line "Depreciation_Allowance"
ERROR - 2020-04-13 14:46:08 --> Could not find the language line "Subscriptions_&_Memberships"
ERROR - 2020-04-13 14:46:08 --> Could not find the language line "Advertising_&_Marketing"
ERROR - 2020-04-13 14:46:08 --> Could not find the language line "Commission"
ERROR - 2020-04-13 14:46:08 --> Could not find the language line "Brand_Amortization"
ERROR - 2020-04-13 14:46:08 --> Could not find the language line "Provision_for_Tax_&_AIT"
ERROR - 2020-04-13 14:46:08 --> Could not find the language line "Provision_for_VAT_Expense"
ERROR - 2020-04-13 14:46:08 --> Could not find the language line "Out-Bound_Courier_Bill_(Sundarban_Courier)"
ERROR - 2020-04-13 14:46:08 --> Could not find the language line "Out-Bound_Courier_Bill_(Sundarban_Courier)-"
ERROR - 2020-04-13 14:46:08 --> Could not find the language line "Travelling_&_Accommodation"
ERROR - 2020-04-13 14:46:08 --> Could not find the language line "Lease_Interest_(Operating_Lease_)"
ERROR - 2020-04-13 14:46:08 --> Could not find the language line "Interest_for_CC?OD_Account"
ERROR - 2020-04-13 14:46:08 --> Could not find the language line "Difference_In_Opening_Balance_-"
ERROR - 2020-04-13 14:46:08 --> Could not find the language line "Empty_Cylinder_Profit_/_Loss-"
INFO - 2020-04-13 14:46:08 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/account/report/day_book_report.php
ERROR - 2020-04-13 14:46:09 --> Could not find the language line "Sales_Lpg_Invoice_add"
ERROR - 2020-04-13 14:46:09 --> Could not find the language line "Report"
ERROR - 2020-04-13 14:46:09 --> Could not find the language line "Product_Wise_Sales"
ERROR - 2020-04-13 14:46:09 --> Could not find the language line "Report"
ERROR - 2020-04-13 14:46:09 --> Could not find the language line "Day_Book"
ERROR - 2020-04-13 14:46:09 --> Could not find the language line "Employee"
ERROR - 2020-04-13 14:46:09 --> Could not find the language line "Branch_Info"
ERROR - 2020-04-13 14:46:09 --> Could not find the language line "Bank_Account_Info"
ERROR - 2020-04-13 14:46:09 --> Could not find the language line "Purchases_lpg_add"
ERROR - 2020-04-13 14:46:09 --> Could not find the language line "2019"
ERROR - 2020-04-13 14:46:09 --> Could not find the language line "Report"
ERROR - 2020-04-13 14:46:09 --> Could not find the language line "Purchases_Invoice"
INFO - 2020-04-13 14:46:09 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/masterTemplate.php
INFO - 2020-04-13 14:46:09 --> Final output sent to browser
DEBUG - 2020-04-13 14:46:09 --> Total execution time: 1.1481
INFO - 2020-04-13 14:46:13 --> Config Class Initialized
INFO - 2020-04-13 14:46:13 --> Hooks Class Initialized
DEBUG - 2020-04-13 14:46:13 --> UTF-8 Support Enabled
INFO - 2020-04-13 14:46:13 --> Utf8 Class Initialized
INFO - 2020-04-13 14:46:13 --> URI Class Initialized
INFO - 2020-04-13 14:46:14 --> Router Class Initialized
INFO - 2020-04-13 14:46:14 --> Output Class Initialized
INFO - 2020-04-13 14:46:14 --> Security Class Initialized
DEBUG - 2020-04-13 14:46:14 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-13 14:46:14 --> Input Class Initialized
INFO - 2020-04-13 14:46:14 --> Language Class Initialized
INFO - 2020-04-13 14:46:14 --> Loader Class Initialized
INFO - 2020-04-13 14:46:14 --> Helper loaded: url_helper
INFO - 2020-04-13 14:46:14 --> Helper loaded: file_helper
INFO - 2020-04-13 14:46:14 --> Helper loaded: utility_helper
INFO - 2020-04-13 14:46:14 --> Helper loaded: unit_helper
INFO - 2020-04-13 14:46:14 --> Helper loaded: multi_language_helper
INFO - 2020-04-13 14:46:14 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-13 14:46:14 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-13 14:46:14 --> Helper loaded: site_helper
INFO - 2020-04-13 14:46:14 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-13 14:46:14 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-13 14:46:14 --> Database Driver Class Initialized
INFO - 2020-04-13 14:46:14 --> Email Class Initialized
DEBUG - 2020-04-13 14:46:14 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-13 14:46:14 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-13 14:46:14 --> Helper loaded: form_helper
INFO - 2020-04-13 14:46:14 --> Form Validation Class Initialized
INFO - 2020-04-13 14:46:14 --> Controller Class Initialized
INFO - 2020-04-13 14:46:14 --> Model "Common_model" initialized
INFO - 2020-04-13 14:46:14 --> Model "Finane_Model" initialized
INFO - 2020-04-13 14:46:14 --> Model "Accounts_model" initialized
INFO - 2020-04-13 14:46:14 --> Model "Inventory_Model" initialized
INFO - 2020-04-13 14:46:14 --> Model "Sales_Model" initialized
INFO - 2020-04-13 14:46:14 --> Model "AccountReport_model" initialized
INFO - 2020-04-13 14:46:14 --> Database Driver Class Initialized
INFO - 2020-04-13 14:46:14 --> Helper loaded: language_helper
INFO - 2020-04-13 14:46:14 --> Language file loaded: language/english/content_lang.php
ERROR - 2020-04-13 14:46:14 --> Could not find the language line "Property_Plant_&_equipment_at_cost"
ERROR - 2020-04-13 14:46:14 --> Could not find the language line "Other_Non_Current_Assets"
ERROR - 2020-04-13 14:46:14 --> Could not find the language line "Investment_in_FDR"
ERROR - 2020-04-13 14:46:14 --> Could not find the language line "Cash_at_Bank"
ERROR - 2020-04-13 14:46:14 --> Could not find the language line "Advance_Deposits_&_Pre-Payments"
ERROR - 2020-04-13 14:46:14 --> Could not find the language line "Advance_against_salary"
ERROR - 2020-04-13 14:46:14 --> Could not find the language line "Advance_for_Expenditures"
ERROR - 2020-04-13 14:46:14 --> Could not find the language line "Advance_Tax_&_VAT"
ERROR - 2020-04-13 14:46:14 --> Could not find the language line "Reserve_&_Surplus"
ERROR - 2020-04-13 14:46:14 --> Could not find the language line "Retained_Earnings"
ERROR - 2020-04-13 14:46:14 --> Could not find the language line "Profit_Loss_Account"
ERROR - 2020-04-13 14:46:14 --> Could not find the language line "Long_Term_Loan_&_Liability-"
ERROR - 2020-04-13 14:46:14 --> Could not find the language line "Inter-Company_Loan_-_Liabilities"
ERROR - 2020-04-13 14:46:14 --> Could not find the language line "Lease_Loan_Liability"
ERROR - 2020-04-13 14:46:14 --> Could not find the language line "Other_Loans_&_Liabilities"
ERROR - 2020-04-13 14:46:14 --> Could not find the language line "Bank_Loan_&_OD_Accounts"
ERROR - 2020-04-13 14:46:14 --> Could not find the language line "Provision_for_Expenses"
ERROR - 2020-04-13 14:46:14 --> Could not find the language line "Provision_for_Income_Tex_&_VAT"
ERROR - 2020-04-13 14:46:14 --> Could not find the language line "Other_Accruals_&_Provisions"
ERROR - 2020-04-13 14:46:14 --> Could not find the language line "Income_From_Commission"
ERROR - 2020-04-13 14:46:14 --> Could not find the language line "Loader's_Payable"
ERROR - 2020-04-13 14:46:14 --> Could not find the language line "Transportation_Payable"
ERROR - 2020-04-13 14:46:14 --> Could not find the language line "Income_From_Incentive"
ERROR - 2020-04-13 14:46:14 --> Could not find the language line "Income_From_Bank_Interest"
ERROR - 2020-04-13 14:46:14 --> Could not find the language line "Income_From_FDR_&_Instruments"
ERROR - 2020-04-13 14:46:14 --> Could not find the language line "Cost_of_Goods_Product"
ERROR - 2020-04-13 14:46:14 --> Could not find the language line "Purchase_Amount"
ERROR - 2020-04-13 14:46:14 --> Could not find the language line "Godown_Rent"
ERROR - 2020-04-13 14:46:14 --> Could not find the language line "Maintenance_Cost"
ERROR - 2020-04-13 14:46:14 --> Could not find the language line "Entertainment"
ERROR - 2020-04-13 14:46:14 --> Could not find the language line "Professional_&_Legal_Fees"
ERROR - 2020-04-13 14:46:14 --> Could not find the language line "Office_Expance"
ERROR - 2020-04-13 14:46:14 --> Could not find the language line "Office_Rent_Current_Period_2020"
ERROR - 2020-04-13 14:46:14 --> Could not find the language line "Depreciation_Allowance"
ERROR - 2020-04-13 14:46:14 --> Could not find the language line "Subscriptions_&_Memberships"
ERROR - 2020-04-13 14:46:14 --> Could not find the language line "Advertising_&_Marketing"
ERROR - 2020-04-13 14:46:14 --> Could not find the language line "Commission"
ERROR - 2020-04-13 14:46:14 --> Could not find the language line "Brand_Amortization"
ERROR - 2020-04-13 14:46:14 --> Could not find the language line "Provision_for_Tax_&_AIT"
ERROR - 2020-04-13 14:46:14 --> Could not find the language line "Provision_for_VAT_Expense"
ERROR - 2020-04-13 14:46:14 --> Could not find the language line "Out-Bound_Courier_Bill_(Sundarban_Courier)"
ERROR - 2020-04-13 14:46:14 --> Could not find the language line "Out-Bound_Courier_Bill_(Sundarban_Courier)-"
ERROR - 2020-04-13 14:46:14 --> Could not find the language line "Travelling_&_Accommodation"
ERROR - 2020-04-13 14:46:14 --> Could not find the language line "Lease_Interest_(Operating_Lease_)"
ERROR - 2020-04-13 14:46:14 --> Could not find the language line "Interest_for_CC?OD_Account"
ERROR - 2020-04-13 14:46:14 --> Could not find the language line "Difference_In_Opening_Balance_-"
ERROR - 2020-04-13 14:46:14 --> Could not find the language line "Empty_Cylinder_Profit_/_Loss-"
INFO - 2020-04-13 14:46:14 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/account/report/day_book_report.php
ERROR - 2020-04-13 14:46:14 --> Could not find the language line "Sales_Lpg_Invoice_add"
ERROR - 2020-04-13 14:46:14 --> Could not find the language line "Report"
ERROR - 2020-04-13 14:46:14 --> Could not find the language line "Product_Wise_Sales"
ERROR - 2020-04-13 14:46:14 --> Could not find the language line "Report"
ERROR - 2020-04-13 14:46:14 --> Could not find the language line "Day_Book"
ERROR - 2020-04-13 14:46:14 --> Could not find the language line "Employee"
ERROR - 2020-04-13 14:46:14 --> Could not find the language line "Branch_Info"
ERROR - 2020-04-13 14:46:14 --> Could not find the language line "Bank_Account_Info"
ERROR - 2020-04-13 14:46:14 --> Could not find the language line "Purchases_lpg_add"
ERROR - 2020-04-13 14:46:14 --> Could not find the language line "2019"
ERROR - 2020-04-13 14:46:14 --> Could not find the language line "Report"
ERROR - 2020-04-13 14:46:14 --> Could not find the language line "Purchases_Invoice"
INFO - 2020-04-13 14:46:14 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/masterTemplate.php
INFO - 2020-04-13 14:46:14 --> Final output sent to browser
DEBUG - 2020-04-13 14:46:14 --> Total execution time: 0.9296
INFO - 2020-04-13 14:46:31 --> Config Class Initialized
INFO - 2020-04-13 14:46:31 --> Hooks Class Initialized
DEBUG - 2020-04-13 14:46:31 --> UTF-8 Support Enabled
INFO - 2020-04-13 14:46:31 --> Utf8 Class Initialized
INFO - 2020-04-13 14:46:31 --> URI Class Initialized
INFO - 2020-04-13 14:46:31 --> Router Class Initialized
INFO - 2020-04-13 14:46:31 --> Output Class Initialized
INFO - 2020-04-13 14:46:31 --> Security Class Initialized
DEBUG - 2020-04-13 14:46:31 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-13 14:46:31 --> Input Class Initialized
INFO - 2020-04-13 14:46:31 --> Language Class Initialized
INFO - 2020-04-13 14:46:31 --> Loader Class Initialized
INFO - 2020-04-13 14:46:31 --> Helper loaded: url_helper
INFO - 2020-04-13 14:46:31 --> Helper loaded: file_helper
INFO - 2020-04-13 14:46:31 --> Helper loaded: utility_helper
INFO - 2020-04-13 14:46:31 --> Helper loaded: unit_helper
INFO - 2020-04-13 14:46:31 --> Helper loaded: multi_language_helper
INFO - 2020-04-13 14:46:31 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-13 14:46:31 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-13 14:46:31 --> Helper loaded: site_helper
INFO - 2020-04-13 14:46:31 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-13 14:46:31 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-13 14:46:31 --> Database Driver Class Initialized
INFO - 2020-04-13 14:46:31 --> Email Class Initialized
DEBUG - 2020-04-13 14:46:31 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-13 14:46:31 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-13 14:46:31 --> Helper loaded: form_helper
INFO - 2020-04-13 14:46:31 --> Form Validation Class Initialized
INFO - 2020-04-13 14:46:31 --> Controller Class Initialized
INFO - 2020-04-13 14:46:31 --> Model "Common_model" initialized
INFO - 2020-04-13 14:46:31 --> Model "Finane_Model" initialized
INFO - 2020-04-13 14:46:31 --> Model "Accounts_model" initialized
INFO - 2020-04-13 14:46:31 --> Model "Inventory_Model" initialized
INFO - 2020-04-13 14:46:31 --> Model "Sales_Model" initialized
INFO - 2020-04-13 14:46:31 --> Model "AccountReport_model" initialized
INFO - 2020-04-13 14:46:31 --> Database Driver Class Initialized
INFO - 2020-04-13 14:46:31 --> Helper loaded: language_helper
INFO - 2020-04-13 14:46:31 --> Language file loaded: language/english/content_lang.php
ERROR - 2020-04-13 14:46:31 --> Could not find the language line "Property_Plant_&_equipment_at_cost"
ERROR - 2020-04-13 14:46:31 --> Could not find the language line "Other_Non_Current_Assets"
ERROR - 2020-04-13 14:46:31 --> Could not find the language line "Investment_in_FDR"
ERROR - 2020-04-13 14:46:31 --> Could not find the language line "Cash_at_Bank"
ERROR - 2020-04-13 14:46:31 --> Could not find the language line "Advance_Deposits_&_Pre-Payments"
ERROR - 2020-04-13 14:46:31 --> Could not find the language line "Advance_against_salary"
ERROR - 2020-04-13 14:46:31 --> Could not find the language line "Advance_for_Expenditures"
ERROR - 2020-04-13 14:46:31 --> Could not find the language line "Advance_Tax_&_VAT"
ERROR - 2020-04-13 14:46:31 --> Could not find the language line "Reserve_&_Surplus"
ERROR - 2020-04-13 14:46:31 --> Could not find the language line "Retained_Earnings"
ERROR - 2020-04-13 14:46:31 --> Could not find the language line "Profit_Loss_Account"
ERROR - 2020-04-13 14:46:31 --> Could not find the language line "Long_Term_Loan_&_Liability-"
ERROR - 2020-04-13 14:46:31 --> Could not find the language line "Inter-Company_Loan_-_Liabilities"
ERROR - 2020-04-13 14:46:31 --> Could not find the language line "Lease_Loan_Liability"
ERROR - 2020-04-13 14:46:31 --> Could not find the language line "Other_Loans_&_Liabilities"
ERROR - 2020-04-13 14:46:31 --> Could not find the language line "Bank_Loan_&_OD_Accounts"
ERROR - 2020-04-13 14:46:31 --> Could not find the language line "Provision_for_Expenses"
ERROR - 2020-04-13 14:46:31 --> Could not find the language line "Provision_for_Income_Tex_&_VAT"
ERROR - 2020-04-13 14:46:31 --> Could not find the language line "Other_Accruals_&_Provisions"
ERROR - 2020-04-13 14:46:31 --> Could not find the language line "Income_From_Commission"
ERROR - 2020-04-13 14:46:31 --> Could not find the language line "Loader's_Payable"
ERROR - 2020-04-13 14:46:31 --> Could not find the language line "Transportation_Payable"
ERROR - 2020-04-13 14:46:32 --> Could not find the language line "Income_From_Incentive"
ERROR - 2020-04-13 14:46:32 --> Could not find the language line "Income_From_Bank_Interest"
ERROR - 2020-04-13 14:46:32 --> Could not find the language line "Income_From_FDR_&_Instruments"
ERROR - 2020-04-13 14:46:32 --> Could not find the language line "Cost_of_Goods_Product"
ERROR - 2020-04-13 14:46:32 --> Could not find the language line "Purchase_Amount"
ERROR - 2020-04-13 14:46:32 --> Could not find the language line "Godown_Rent"
ERROR - 2020-04-13 14:46:32 --> Could not find the language line "Maintenance_Cost"
ERROR - 2020-04-13 14:46:32 --> Could not find the language line "Entertainment"
ERROR - 2020-04-13 14:46:32 --> Could not find the language line "Professional_&_Legal_Fees"
ERROR - 2020-04-13 14:46:32 --> Could not find the language line "Office_Expance"
ERROR - 2020-04-13 14:46:32 --> Could not find the language line "Office_Rent_Current_Period_2020"
ERROR - 2020-04-13 14:46:32 --> Could not find the language line "Depreciation_Allowance"
ERROR - 2020-04-13 14:46:32 --> Could not find the language line "Subscriptions_&_Memberships"
ERROR - 2020-04-13 14:46:32 --> Could not find the language line "Advertising_&_Marketing"
ERROR - 2020-04-13 14:46:32 --> Could not find the language line "Commission"
ERROR - 2020-04-13 14:46:32 --> Could not find the language line "Brand_Amortization"
ERROR - 2020-04-13 14:46:32 --> Could not find the language line "Provision_for_Tax_&_AIT"
ERROR - 2020-04-13 14:46:32 --> Could not find the language line "Provision_for_VAT_Expense"
ERROR - 2020-04-13 14:46:32 --> Could not find the language line "Out-Bound_Courier_Bill_(Sundarban_Courier)"
ERROR - 2020-04-13 14:46:32 --> Could not find the language line "Out-Bound_Courier_Bill_(Sundarban_Courier)-"
ERROR - 2020-04-13 14:46:32 --> Could not find the language line "Travelling_&_Accommodation"
ERROR - 2020-04-13 14:46:32 --> Could not find the language line "Lease_Interest_(Operating_Lease_)"
ERROR - 2020-04-13 14:46:32 --> Could not find the language line "Interest_for_CC?OD_Account"
ERROR - 2020-04-13 14:46:32 --> Could not find the language line "Difference_In_Opening_Balance_-"
ERROR - 2020-04-13 14:46:32 --> Could not find the language line "Empty_Cylinder_Profit_/_Loss-"
INFO - 2020-04-13 14:46:32 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/account/report/day_book_report.php
ERROR - 2020-04-13 14:46:32 --> Could not find the language line "Sales_Lpg_Invoice_add"
ERROR - 2020-04-13 14:46:32 --> Could not find the language line "Report"
ERROR - 2020-04-13 14:46:32 --> Could not find the language line "Product_Wise_Sales"
ERROR - 2020-04-13 14:46:32 --> Could not find the language line "Report"
ERROR - 2020-04-13 14:46:32 --> Could not find the language line "Day_Book"
ERROR - 2020-04-13 14:46:32 --> Could not find the language line "Employee"
ERROR - 2020-04-13 14:46:32 --> Could not find the language line "Branch_Info"
ERROR - 2020-04-13 14:46:32 --> Could not find the language line "Bank_Account_Info"
ERROR - 2020-04-13 14:46:32 --> Could not find the language line "Purchases_lpg_add"
ERROR - 2020-04-13 14:46:32 --> Could not find the language line "2019"
ERROR - 2020-04-13 14:46:32 --> Could not find the language line "Report"
ERROR - 2020-04-13 14:46:32 --> Could not find the language line "Purchases_Invoice"
INFO - 2020-04-13 14:46:32 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\distributor/masterTemplate.php
INFO - 2020-04-13 14:46:32 --> Final output sent to browser
DEBUG - 2020-04-13 14:46:32 --> Total execution time: 0.9388
INFO - 2020-04-13 17:11:30 --> Config Class Initialized
INFO - 2020-04-13 17:11:30 --> Hooks Class Initialized
DEBUG - 2020-04-13 17:11:30 --> UTF-8 Support Enabled
INFO - 2020-04-13 17:11:30 --> Utf8 Class Initialized
INFO - 2020-04-13 17:11:30 --> URI Class Initialized
INFO - 2020-04-13 17:11:30 --> Router Class Initialized
INFO - 2020-04-13 17:11:30 --> Output Class Initialized
INFO - 2020-04-13 17:11:30 --> Security Class Initialized
DEBUG - 2020-04-13 17:11:30 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-13 17:11:30 --> Input Class Initialized
INFO - 2020-04-13 17:11:30 --> Language Class Initialized
INFO - 2020-04-13 17:11:30 --> Loader Class Initialized
INFO - 2020-04-13 17:11:30 --> Helper loaded: url_helper
INFO - 2020-04-13 17:11:30 --> Helper loaded: file_helper
INFO - 2020-04-13 17:11:30 --> Helper loaded: utility_helper
INFO - 2020-04-13 17:11:30 --> Helper loaded: unit_helper
INFO - 2020-04-13 17:11:30 --> Helper loaded: multi_language_helper
INFO - 2020-04-13 17:11:30 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-13 17:11:30 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-13 17:11:30 --> Helper loaded: site_helper
INFO - 2020-04-13 17:11:30 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-13 17:11:30 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-13 17:11:30 --> Database Driver Class Initialized
INFO - 2020-04-13 17:11:30 --> Email Class Initialized
DEBUG - 2020-04-13 17:11:30 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-13 17:11:30 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-13 17:11:30 --> Helper loaded: form_helper
INFO - 2020-04-13 17:11:30 --> Form Validation Class Initialized
INFO - 2020-04-13 17:11:30 --> Controller Class Initialized
INFO - 2020-04-13 17:11:30 --> Model "Common_model" initialized
INFO - 2020-04-13 17:11:30 --> Model "Finane_Model" initialized
INFO - 2020-04-13 17:11:31 --> Model "Accounts_model" initialized
INFO - 2020-04-13 17:11:31 --> Model "Inventory_Model" initialized
INFO - 2020-04-13 17:11:31 --> Model "Sales_Model" initialized
INFO - 2020-04-13 17:11:31 --> Model "AccountReport_model" initialized
INFO - 2020-04-13 17:11:31 --> Config Class Initialized
INFO - 2020-04-13 17:11:31 --> Hooks Class Initialized
DEBUG - 2020-04-13 17:11:31 --> UTF-8 Support Enabled
INFO - 2020-04-13 17:11:31 --> Utf8 Class Initialized
INFO - 2020-04-13 17:11:31 --> URI Class Initialized
DEBUG - 2020-04-13 17:11:31 --> No URI present. Default controller set.
INFO - 2020-04-13 17:11:31 --> Router Class Initialized
INFO - 2020-04-13 17:11:31 --> Output Class Initialized
INFO - 2020-04-13 17:11:31 --> Security Class Initialized
DEBUG - 2020-04-13 17:11:31 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-13 17:11:31 --> Input Class Initialized
INFO - 2020-04-13 17:11:31 --> Language Class Initialized
INFO - 2020-04-13 17:11:31 --> Loader Class Initialized
INFO - 2020-04-13 17:11:31 --> Helper loaded: url_helper
INFO - 2020-04-13 17:11:31 --> Helper loaded: file_helper
INFO - 2020-04-13 17:11:31 --> Helper loaded: utility_helper
INFO - 2020-04-13 17:11:31 --> Helper loaded: unit_helper
INFO - 2020-04-13 17:11:31 --> Helper loaded: multi_language_helper
INFO - 2020-04-13 17:11:31 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-13 17:11:31 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-13 17:11:31 --> Helper loaded: site_helper
INFO - 2020-04-13 17:11:31 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-13 17:11:31 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-13 17:11:31 --> Database Driver Class Initialized
INFO - 2020-04-13 17:11:31 --> Email Class Initialized
DEBUG - 2020-04-13 17:11:31 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-13 17:11:31 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-13 17:11:31 --> Helper loaded: form_helper
INFO - 2020-04-13 17:11:31 --> Form Validation Class Initialized
INFO - 2020-04-13 17:11:31 --> Controller Class Initialized
INFO - 2020-04-13 17:11:31 --> Model "Common_model" initialized
INFO - 2020-04-13 17:11:31 --> Helper loaded: language_helper
INFO - 2020-04-13 17:11:31 --> Language file loaded: language/english/content_lang.php
INFO - 2020-04-13 17:11:31 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\auth/login.php
INFO - 2020-04-13 17:11:31 --> Final output sent to browser
DEBUG - 2020-04-13 17:11:31 --> Total execution time: 0.3272
INFO - 2020-04-13 17:11:31 --> Config Class Initialized
INFO - 2020-04-13 17:11:31 --> Hooks Class Initialized
DEBUG - 2020-04-13 17:11:31 --> UTF-8 Support Enabled
INFO - 2020-04-13 17:11:31 --> Utf8 Class Initialized
INFO - 2020-04-13 17:11:31 --> URI Class Initialized
INFO - 2020-04-13 17:11:31 --> Router Class Initialized
INFO - 2020-04-13 17:11:31 --> Output Class Initialized
INFO - 2020-04-13 17:11:31 --> Security Class Initialized
DEBUG - 2020-04-13 17:11:31 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-13 17:11:31 --> Input Class Initialized
INFO - 2020-04-13 17:11:31 --> Language Class Initialized
ERROR - 2020-04-13 17:11:31 --> 404 Page Not Found: Assets/global
INFO - 2020-04-13 17:11:31 --> Config Class Initialized
INFO - 2020-04-13 17:11:32 --> Hooks Class Initialized
DEBUG - 2020-04-13 17:11:32 --> UTF-8 Support Enabled
INFO - 2020-04-13 17:11:32 --> Utf8 Class Initialized
INFO - 2020-04-13 17:11:32 --> URI Class Initialized
INFO - 2020-04-13 17:11:32 --> Router Class Initialized
INFO - 2020-04-13 17:11:32 --> Output Class Initialized
INFO - 2020-04-13 17:11:32 --> Security Class Initialized
DEBUG - 2020-04-13 17:11:32 --> Global POST, GET and COOKIE data sanitized
INFO - 2020-04-13 17:11:32 --> Input Class Initialized
INFO - 2020-04-13 17:11:32 --> Language Class Initialized
INFO - 2020-04-13 17:11:32 --> Loader Class Initialized
INFO - 2020-04-13 17:11:32 --> Helper loaded: url_helper
INFO - 2020-04-13 17:11:32 --> Helper loaded: file_helper
INFO - 2020-04-13 17:11:32 --> Helper loaded: utility_helper
INFO - 2020-04-13 17:11:32 --> Helper loaded: unit_helper
INFO - 2020-04-13 17:11:32 --> Helper loaded: multi_language_helper
INFO - 2020-04-13 17:11:32 --> Helper loaded: db_dinamic_helper
INFO - 2020-04-13 17:11:32 --> Helper loaded: create_voucher_no_helper
INFO - 2020-04-13 17:11:32 --> Helper loaded: site_helper
INFO - 2020-04-13 17:11:32 --> Helper loaded: branch_dropdown_helper
INFO - 2020-04-13 17:11:32 --> Helper loaded: account_ledger_dropdown_helper
INFO - 2020-04-13 17:11:32 --> Database Driver Class Initialized
INFO - 2020-04-13 17:11:32 --> Email Class Initialized
DEBUG - 2020-04-13 17:11:32 --> Session: "sess_save_path" is empty; using "session.save_path" value from php.ini.
INFO - 2020-04-13 17:11:32 --> Session: Class initialized using 'files' driver.
INFO - 2020-04-13 17:11:32 --> Helper loaded: form_helper
INFO - 2020-04-13 17:11:32 --> Form Validation Class Initialized
INFO - 2020-04-13 17:11:32 --> Controller Class Initialized
INFO - 2020-04-13 17:11:32 --> Model "Common_model" initialized
INFO - 2020-04-13 17:11:32 --> Helper loaded: language_helper
INFO - 2020-04-13 17:11:32 --> Language file loaded: language/english/content_lang.php
INFO - 2020-04-13 17:11:32 --> File loaded: H:\XAMPP\codeigniter\htdocs\masterProject\application\views\auth/login.php
INFO - 2020-04-13 17:11:32 --> Final output sent to browser
DEBUG - 2020-04-13 17:11:32 --> Total execution time: 0.3380
