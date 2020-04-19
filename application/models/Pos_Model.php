<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pos_Model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();













    }

    public function get_pos_data($pos_id = 36,$dist_id=15) {

        $this->db->select("g.dist_id,g.form_id,g.payType,g.voucher_no,g.customer_id,p.brand_id,g.reference,g.date,g.discount,g.vat,g.debit,g.credit,g.vatAmount,g.narration,c.customerName,s.generals_id,s.category_id,s.unit,s.product_id,s.type,s.quantity,s.rate,s.price,pc.title,p.productName,u.unitTtile,a.name as salesPerson,b.brandName");
        $this->db->from("generals g");
        $this->db->join('customer c', 'c.customer_id=g.customer_id');

        $this->db->join('admin a', 'a.admin_id=g.updated_by');
        $this->db->join('stock s', 'g.generals_id=s.generals_id');
        $this->db->join('productcategory pc', 'pc.category_id=s.category_id');
        $this->db->join('product p', 'p.product_id=s.product_id');
        $this->db->join('brand b', 'p.brand_id=b.brandId');
        $this->db->join('unit u', 'u.unit_id=s.unit','left');

        $this->db->where('g.generals_id', $pos_id);
        $this->db->where('g.form_id', 31);
        $this->db->where('g.dist_id', $dist_id);
        $this->db->where('a.distributor_id', $dist_id);

        $result = $this->db->get()->result();

        return $result;
    }

      public function getSalesPosVoucherList() {
        $this->db->select("form.name,generals.generals_id,generals.voucher_no,generals.narration,generals.date,generals.debit,customer.customerID,customer.customerName,customer.customer_id");
        $this->db->from("generals");
        $this->db->join('customer', 'customer.customer_id=generals.customer_id');
        $this->db->join('form', 'form.form_id=generals.form_id');
        $this->db->where('generals.dist_id', $this->dist_id);
        $this->db->where('generals.form_id', 31);
        $this->db->order_by('generals.date', 'desc');
        $result = $this->db->get()->result();
        return $result;
    }

    function abc(){
        $query="SELECT 
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
       
       WHERE  pd.is_active='Y' AND pd.is_delete='N' AND pii.invoice_date < '2019-12-29'   
       
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
       AND pii.invoice_date < '2019-12-29' 
               
       
       GROUP BY pd.product_id      
      
       ) purchase ON  BaseT.product_id= purchase.product_id LEFT  OUTER JOIN
       
    (
SELECT   
        sr.product_id,  SUM(sr.return_quantity)  Srt_quantity
      , SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0) AS Srt_UPrice       
     ,(SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0))*(SUM(sr.return_quantity) )  Srt_Amount
       
  FROM sales_return sr   
       
       WHERE  sr.is_active='Y' AND sr.is_delete='N'    
       AND sr.return_date < '2019-12-29' 
         
       
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
 AND sii.invoice_date < '2019-12-29'  
       
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
 AND iai.date < '2019-12-29' 
       
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
       AND pii.invoice_date >= '2019-12-29' 
       AND pii.invoice_date <= '2020-01-25'          
       
       GROUP BY pd.product_id      
      
       ) purchase ON  BaseT.product_id= purchase.product_id LEFT  OUTER JOIN
       
    (
SELECT   
        sr.product_id,  SUM(sr.return_quantity)  Srt_quantity
      , SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0) AS Srt_UPrice       
     ,(SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0))*(SUM(sr.return_quantity) )  Srt_Amount
       
  FROM sales_return sr   
       
       WHERE  sr.is_active='Y' AND sr.is_delete='N'    
       AND sr.return_date >= '2019-12-29'
       AND sr.return_date <= '2020-01-25' 
         
       
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
 AND sii.invoice_date >= '2019-12-29'
AND sii.invoice_date <= '2020-01-25' 
       
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
 AND iai.date >= '2019-12-29'
AND iai.date <= '2020-01-25'  
       
       GROUP BY iad.product_id     
        ) Inv_Adj ON Inv_Adj.product_id=BaseT.product_id 
        
        
        
            ) Tol LEFT JOIN package_products on Tol.product_id=package_products.product_id
LEFT JOIN (
SELECT product.category_id,package_products.product_id,package_products.package_id 
FROM package_products LEFT JOIN product 
ON product.product_id=package_products.product_id 
WHERE product.category_id=1
) pp2 on pp2.package_id=package_products.package_id

LEFT JOIN (

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
 AND purchase_invoice_info.invoice_date < '2019-12-29'
  
 

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
  AND purchase_invoice_info.invoice_date < '2019-12-29'   
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
 AND sales_invoice_info.invoice_date < '2019-12-29'
  
 

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
AND purchase_invoice_info.invoice_date < '2019-12-29'
  

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
AND purchase_invoice_info.invoice_date < '2019-12-29'
  

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
AND sales_invoice_info.invoice_date < '2019-12-29'
     

GROUP BY   sales_return_details.product_id) Ret_Sales ON   BaseT.product_id= Ret_Sales.product_id  LEFT  OUTER JOIN  


/*END ReturnSales*/
 
 (
SELECT   
        sr.product_id,  SUM(sr.return_quantity)  Srt_quantity
      , SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0) AS Srt_UPrice       
     ,(SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0))*(SUM(sr.return_quantity) )  Srt_Amount
       
  FROM sales_return sr   
       
       WHERE  sr.is_active='Y' AND sr.is_delete='N'    
       AND sr.return_date < '2019-12-29'
         
       
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
 AND iai.date < '2019-12-29'
       
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


AND purchase_invoice_info.invoice_date  >= '2019-12-29'
 AND purchase_invoice_info.invoice_date  <= '2020-01-25'    
 
 
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


AND purchase_invoice_info.invoice_date  >= '2019-12-29'
 AND purchase_invoice_info.invoice_date  <= '2020-01-25'    
 
    
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

AND sales_invoice_info.invoice_date  >= '2019-12-29'
 AND sales_invoice_info.invoice_date  <= '2020-01-25'    
 
 
  
 

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


AND  purchase_invoice_info.invoice_date  >= '2019-12-29'
 AND  purchase_invoice_info.invoice_date <= '2020-01-25'    
 
 
  

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

AND  purchase_invoice_info.invoice_date  >= '2019-12-29'
 AND  purchase_invoice_info.invoice_date  <= '2020-01-25'   
 
 
  

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
AND  sales_invoice_info.invoice_date  >= '2019-12-29'
 AND  sales_invoice_info.invoice_date  <= '2020-01-25'  
 
     

GROUP BY   sales_return_details.product_id) Ret_Sales ON   BaseT.product_id= Ret_Sales.product_id  LEFT  OUTER JOIN  


/*END ReturnSales*/
 
 (
SELECT   
        sr.product_id,  SUM(sr.return_quantity)  Srt_quantity
      , SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0) AS Srt_UPrice       
     ,(SUM(sr.return_quantity * sr.unit_price) / NULLIF(SUM(sr.return_quantity),0))*(SUM(sr.return_quantity) )  Srt_Amount
       
  FROM sales_return sr   
       
       WHERE  sr.is_active='Y' AND sr.is_delete='N'    
        
         AND sr.return_date  >= '2019-12-29'
 AND sr.return_date  <= '2020-01-25'   
       
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
 AND iai.date >= '2019-12-29'
AND iai.date <= '2020-01-25'  
       
       GROUP BY iad.product_id     
        ) Inv_Adj ON Inv_Adj.product_id=BaseT.product_id 
        
        
) TolEmpty on TolEmpty.product_id=pp2.product_id


WHERE 1=1";

    }
}
